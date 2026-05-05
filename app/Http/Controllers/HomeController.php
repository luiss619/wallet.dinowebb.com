<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $year     = (int) $request->input('year', date('Y'));
        $accounts = Account::where('user_id', Auth::id())
            ->where('status', 1)
            ->orderBy('id')
            ->get();

        $month_names = array_map(
            fn($n) => \Carbon\Carbon::create(2000, $n)->locale('es')->isoFormat('MMMM'),
            range(1, 12)
        );

        $jan_starts = [];
        foreach ($accounts as $account) {
            $pre_year = (float) Movement::where('account_id', $account->id)
                ->where('user_id', Auth::id())
                ->where('date', '<', "{$year}-01-01")
                ->sum('quantity');
            $jan_starts[$account->id] = (float) $account->balance + $pre_year;
        }

        $months             = [];
        $cumulative_savings = 0.0;

        foreach ($month_names as $idx => $month_name) {
            $month_num  = $idx + 1;
            $start_date = sprintf('%04d-%02d-01', $year, $month_num);
            $end_date   = date('Y-m-t', strtotime($start_date));

            $month_data = [
                'name'       => $month_name,
                'name_short' => strtoupper(substr($month_name, 0, 3)),
                'quarter'    => 'Trimestre ' . ceil($month_num / 3),
                'start_date' => $start_date,
                'end_date'   => $end_date,
                'accounts'   => [],
            ];

            foreach ($accounts as $account) {
                $start = $idx === 0
                    ? $jan_starts[$account->id]
                    : ($months[$idx - 1]['accounts'][$account->id]['balance'] ?? 0.0);

                $base = Movement::where('account_id', $account->id)
                    ->where('user_id', Auth::id())
                    ->whereBetween('date', [$start_date, $end_date]);

                $income    = (float) (clone $base)->where('type', 0)->where('quantity', '>', 0)->sum('quantity');
                $expenses  = (float) (clone $base)->where('type', 0)->where('quantity', '<', 0)->sum('quantity');
                $transfers = (float) (clone $base)->where('type', 1)->sum('quantity');
                $savings   = (float) (clone $base)->where('type', 2)->sum('quantity');

                $month_data['accounts'][$account->id] = [
                    'name'      => $account->name,
                    'start'     => $start,
                    'income'    => $income,
                    'expenses'  => $expenses,
                    'transfers' => $transfers,
                    'savings'   => $savings,
                    'balance'   => $start + $income + $expenses + $transfers - $savings,
                ];
            }

            $month_data['total'] = [
                'name'      => 'TOTAL',
                'start'     => array_sum(array_column($month_data['accounts'], 'start')),
                'income'    => array_sum(array_column($month_data['accounts'], 'income')),
                'expenses'  => array_sum(array_column($month_data['accounts'], 'expenses')),
                'transfers' => array_sum(array_column($month_data['accounts'], 'transfers')),
                'savings'   => array_sum(array_column($month_data['accounts'], 'savings')),
                'balance'   => array_sum(array_column($month_data['accounts'], 'balance')),
            ];

            $cumulative_savings          += $month_data['total']['savings'];
            $has_data                     = $month_data['total']['income'] != 0
                                         || $month_data['total']['expenses'] != 0
                                         || $month_data['total']['transfers'] != 0
                                         || $month_data['total']['savings'] != 0;
            $month_data['has_data']       = $has_data;
            $month_data['net']            = $month_data['total']['balance'];
            $month_data['cumulative_savings'] = $cumulative_savings;

            $months[] = $month_data;
        }

        $min_year_date      = Movement::where('user_id', Auth::id())->min('date');
        $min_year           = $min_year_date ? (int) substr($min_year_date, 0, 4) : (int) date('Y');
        $max_year           = (int) date('Y');
        $year_range         = range(min($min_year, $max_year), $max_year);

        $yearly_income      = array_sum(array_map(fn($m) => $m['total']['income'],   $months));
        $yearly_expenses    = array_sum(array_map(fn($m) => $m['total']['expenses'], $months));
        $yearly_net         = $yearly_income + $yearly_expenses;

        $latest_month       = collect($months)->last(fn($m) => $m['has_data']);
        $total_assets       = $latest_month ? $latest_month['total']['balance'] : 0;
        $latest_net         = $latest_month ? $latest_month['net'] : 0;
        $latest_start       = $latest_month ? $latest_month['total']['start'] : 0;
        $monthly_growth_pct = $latest_start != 0 ? round($latest_net / abs($latest_start) * 100, 1) : 0;
        $savings_rate       = $yearly_income > 0 ? max(0, min(100, round($yearly_net / $yearly_income * 100))) : 0;

        return view('home.index', compact(
            'months', 'year', 'year_range', 'accounts',
            'yearly_income', 'yearly_expenses', 'yearly_net',
            'total_assets', 'latest_net', 'monthly_growth_pct', 'savings_rate'
        ));
    }

    public function month(Request $request, int $year, int $month)
    {
        $account_id = $request->input('account_id');
        $start_date = sprintf('%04d-%02d-01', $year, $month);
        $end_date   = date('Y-m-t', strtotime($start_date));

        $query = Movement::where('user_id', Auth::id())
            ->whereBetween('date', [$start_date, $end_date])
            ->with('account', 'service.category')
            ->orderBy('date');

        if ($account_id) {
            $query->where('account_id', $account_id);
        }

        $movements = $query->get();

        $income    = $movements->filter(fn($m) => $m->type == 0 && $m->quantity > 0)->sortByDesc('quantity');
        $expenses  = $movements->filter(fn($m) => $m->type == 0 && $m->quantity < 0)->sortBy('quantity');
        $transfers = $movements->where('type', 1)->sortBy('date');
        $savings   = $movements->where('type', 2)->sortBy('date');

        $month_name   = ucfirst(\Carbon\Carbon::create($year, $month)->locale('es')->isoFormat('MMMM'));
        $account_name = null;
        if ($account_id) {
            $account      = Account::where('id', $account_id)->where('user_id', Auth::id())->first();
            $account_name = $account?->name;
        }

        $accounts_for_balance = Account::where('user_id', Auth::id())->where('status', 1)
            ->when($account_id, fn($q) => $q->where('id', $account_id))
            ->get();

        $total_balance = 0;
        foreach ($accounts_for_balance as $acc) {
            $pre_movements = Movement::where('account_id', $acc->id)
                ->where('user_id', Auth::id())
                ->where('date', '<', $start_date)
                ->get();
            $pre_balance = $pre_movements->filter(fn($m) => $m->type != 2)->sum('quantity')
                         - $pre_movements->where('type', 2)->sum('quantity');
            $start = (float) $acc->balance + $pre_balance;

            $acc_movements = $movements->where('account_id', $acc->id);
            $acc_income    = $acc_movements->filter(fn($m) => $m->type == 0 && $m->quantity > 0)->sum('quantity');
            $acc_expenses  = $acc_movements->filter(fn($m) => $m->type == 0 && $m->quantity < 0)->sum('quantity');
            $acc_transfers = $acc_movements->where('type', 1)->sum('quantity');
            $acc_savings   = $acc_movements->where('type', 2)->sum('quantity');

            $total_balance += $start + $acc_income + $acc_expenses + $acc_transfers - $acc_savings;
        }

        $total_income    = $income->sum('quantity');
        $total_expenses  = $expenses->sum('quantity');
        $total_transfers = $transfers->sum('quantity');
        $total_savings   = $savings->sum('quantity');

        $income_by_service = $income
            ->groupBy(fn($m) => $m->service->name ?? 'Sin servicio')
            ->map(fn($g) => round($g->sum('quantity'), 2))
            ->sortDesc();

        $expenses_by_category = $expenses
            ->groupBy(fn($m) => $m->service->category->name ?? 'Sin categoría')
            ->map(fn($g) => round(abs($g->sum('quantity')), 2))
            ->sortDesc();

        $income_chart_data   = $this->buildChartData($income_by_service, 'green');
        $expenses_chart_data = $this->buildChartData($expenses_by_category, 'red');

        return view('home.month', compact(
            'income', 'expenses', 'transfers', 'savings',
            'year', 'month', 'month_name', 'account_id', 'account_name',
            'total_balance', 'total_income', 'total_expenses', 'total_transfers', 'total_savings',
            'income_chart_data', 'expenses_chart_data'
        ));
    }

    private function buildChartData(\Illuminate\Support\Collection $data, string $color_set): array
    {
        $colors = $color_set === 'green'
            ? ['#198754','#20c997','#0dcaf0','#0d6efd','#6610f2','#6f42c1','#d63384','#fd7e14','#ffc107']
            : ['#dc3545','#fd7e14','#ffc107','#e91e63','#9c27b0','#673ab7','#3f51b5','#2196f3','#00bcd4'];

        return [
            'series' => array_values($data->toArray()),
            'labels' => array_keys($data->toArray()),
            'colors' => $colors,
        ];
    }
}
