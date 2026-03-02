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
            ->orderBy('name')
            ->get();

        $monthNames = [
            'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December',
        ];

        // account.balance = opening/reference balance (before all recorded movements).
        // January start = account.balance + SUM(all movements before year-01-01).
        // Subsequent months chain from previous month's ending balance.
        $janStarts = [];
        foreach ($accounts as $account) {
            $preYear = (float) Movement::where('account_id', $account->id)
                ->where('user_id', Auth::id())
                ->where('date', '<', "{$year}-01-01")
                ->sum('quantity');
            $janStarts[$account->id] = (float) $account->balance + $preYear;
        }

        $months = [];

        foreach ($monthNames as $idx => $monthName) {
            $monthNum  = $idx + 1;
            $startDate = sprintf('%04d-%02d-01', $year, $monthNum);
            $endDate   = date('Y-m-t', strtotime($startDate));

            $monthData = [
                'name'       => $monthName,
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'accounts'   => [],
            ];

            foreach ($accounts as $account) {
                $start = $idx === 0
                    ? $janStarts[$account->id]
                    : ($months[$idx - 1]['accounts'][$account->id]['balance'] ?? 0.0);

                $base = Movement::where('account_id', $account->id)
                    ->where('user_id', Auth::id())
                    ->whereBetween('date', [$startDate, $endDate]);

                $income    = (float) (clone $base)->where('type', 0)->where('quantity', '>', 0)->sum('quantity');
                $expenses  = (float) (clone $base)->where('type', 0)->where('quantity', '<', 0)->sum('quantity');
                $transfers = (float) (clone $base)->where('type', 1)->sum('quantity');

                $monthData['accounts'][$account->id] = [
                    'name'      => $account->name,
                    'start'     => $start,
                    'income'    => $income,
                    'expenses'  => $expenses,
                    'transfers' => $transfers,
                    'balance'   => $start + $income + $expenses + $transfers,
                ];
            }

            // TOTAL row
            $monthData['total'] = [
                'name'      => 'TOTAL',
                'start'     => array_sum(array_column($monthData['accounts'], 'start')),
                'income'    => array_sum(array_column($monthData['accounts'], 'income')),
                'expenses'  => array_sum(array_column($monthData['accounts'], 'expenses')),
                'transfers' => array_sum(array_column($monthData['accounts'], 'transfers')),
                'balance'   => array_sum(array_column($monthData['accounts'], 'balance')),
            ];

            $months[] = $monthData;
        }

        // Year range for selector
        $minYearDate = Movement::where('user_id', Auth::id())->min('date');
        $minYear     = $minYearDate ? (int) substr($minYearDate, 0, 4) : (int) date('Y');
        $maxYear     = (int) date('Y');
        $yearRange   = range(min($minYear, $maxYear), $maxYear);

        // Yearly totals (transfers excluded from income/expense cards)
        $yearlyIncome   = array_sum(array_map(fn($m) => $m['total']['income'],   $months));
        $yearlyExpenses = array_sum(array_map(fn($m) => $m['total']['expenses'], $months));
        $yearlyNet      = $yearlyIncome + $yearlyExpenses;

        return view('home.index', compact(
            'months', 'year', 'yearRange',
            'yearlyIncome', 'yearlyExpenses', 'yearlyNet',
            'accounts'
        ));
    }
}
