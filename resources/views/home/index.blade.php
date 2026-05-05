@extends('layouts.vertical', ['title' => 'Dashboard'])

@section('content')

<div class="text-center mt-4 mb-2">
    <div class="d-flex justify-content-center gap-2">
        @foreach($year_range as $y)
            <a href="?year={{ $y }}"
               class="btn btn-sm rounded-pill px-3 {{ $y == $year ? 'btn-dark' : 'btn-light' }}">
                {{ $y }}
            </a>
        @endforeach
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="fw-bold mb-0 text-uppercase">Resumen Anual</h5>
</div>
@php
    $chart_labels   = [];
    $chart_income   = [];
    $chart_expenses = [];
    $chart_savings  = [];
    foreach ($months as $m) {
        $chart_labels[]   = $m['name_short'];
        $chart_income[]   = $m['has_data'] ? round($m['total']['income'], 2) : null;
        $chart_expenses[] = $m['has_data'] ? round(abs($m['total']['expenses']), 2) : null;
        $chart_savings[]  = $m['has_data'] ? round($m['cumulative_savings'], 2) : null;
    }
@endphp
<div class="row g-3 mb-4">
    {{-- Tabla izquierda --}}
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-body p-0">
                <table class="table table-sm mb-0" style="font-size:.72rem;">
                    <thead style="background:#f8f9fa;">
                        <tr class="text-uppercase text-muted" style="font-size:.62rem; letter-spacing:.4px;">
                            <th class="ps-3 py-2">Mes</th>
                            <th class="text-end py-2">Ingreso</th>
                            <th class="text-end py-2">Gasto</th>
                            <th class="text-end py-2">Ahorro</th>
                            <th class="text-end py-2">Ahorro acumulado</th>
                            <th class="text-end pe-3 py-2">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($months as $i => $month)
                        @php
                            $balance_color = $month['has_data'] ? ($month['total']['balance'] >= 0 ? '#198754' : '#dc3545') : '#adb5bd';
                        @endphp
                        <tr class="{{ !$month['has_data'] ? 'opacity-50' : '' }}">
                            <td class="ps-3 py-1">
                                @if($month['has_data'])
                                <a href="{{ route('home.month', [$year, $i + 1]) }}" class="home-link fw-bold text-uppercase" style="font-size:.7rem; letter-spacing:.4px;">{{ $month['name_short'] }}</a>
                                @else
                                <span class="fw-bold text-uppercase text-muted" style="font-size:.7rem; letter-spacing:.4px;">{{ $month['name_short'] }}</span>
                                @endif
                            </td>
                            @if($month['has_data'])
                            <td class="text-end text-success fw-semibold">+{{ number_format($month['total']['income'], 2, ',', '.') }}€</td>
                            <td class="text-end text-danger fw-semibold">{{ number_format($month['total']['expenses'], 2, ',', '.') }}€</td>
                            <td class="text-end fw-semibold" style="{{ $month['total']['savings'] != 0 ? 'color:#6ea8fe;' : 'color:#adb5bd;' }}">{{ number_format($month['total']['savings'], 2, ',', '.') }}€</td>
                            <td class="text-end fw-semibold {{ $month['cumulative_savings'] != 0 ? 'text-dark' : 'text-muted' }}">{{ number_format($month['cumulative_savings'], 2, ',', '.') }}€</td>
                            <td class="text-end pe-3 fw-bold" style="color: {{ $balance_color }};">{{ number_format($month['total']['balance'], 2, ',', '.') }}€</td>
                            @else
                            <td colspan="5" class="text-end pe-3 text-muted py-1">—</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Gráfico derecha --}}
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-center">
                <div id="annualChart"
                     data-labels="{{ json_encode($chart_labels) }}"
                     data-income="{{ json_encode($chart_income) }}"
                     data-expenses="{{ json_encode($chart_expenses) }}"
                     data-savings="{{ json_encode($chart_savings) }}"
                     style="width:100%; min-height:300px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <h5 class="fw-bold mb-0 text-uppercase">Resumen Mensual</h5>
</div>

@if(!empty($expense_categories))
<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <h5 class="fw-bold mb-0 text-uppercase">Gastos por Categoría</h5>
</div>
<div class="card mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0" style="font-size:.72rem;">
                <thead style="background:#f8f9fa;">
                    <tr class="text-uppercase text-muted" style="font-size:.62rem; letter-spacing:.4px;">
                        <th class="ps-3 py-2">Mes</th>
                        @foreach($expense_categories as $cat)
                            <th class="text-end py-2">{{ $cat }}</th>
                        @endforeach
                        <th class="text-end pe-3 py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($months as $i => $month)
                    @php
                        $month_num   = $i + 1;
                        $month_pivot = $expenses_pivot[$month_num] ?? [];
                        $row_total   = array_sum($month_pivot);
                        $has_row     = $row_total != 0;
                    @endphp
                    <tr class="{{ !$has_row ? 'opacity-50' : '' }}">
                        <td class="ps-3 py-1 fw-bold text-uppercase" style="font-size:.7rem; letter-spacing:.4px; white-space:nowrap;">
                            {{ $month['name_short'] }}
                        </td>
                        @foreach($expense_categories as $cat)
                        @php $val = $month_pivot[$cat] ?? 0; @endphp
                        <td class="text-end py-1 {{ $val != 0 ? 'text-dark' : 'text-muted' }}">
                            {{ $val != 0 ? number_format($val, 2, ',', '.') . ' €' : '—' }}
                        </td>
                        @endforeach
                        <td class="text-end pe-3 py-1 fw-bold {{ $row_total != 0 ? 'text-danger' : 'text-muted' }}">
                            {{ $row_total != 0 ? number_format($row_total, 2, ',', '.') . ' €' : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot style="background:#f8f9fa; border-top:2px solid #dee2e6;">
                    <tr class="fw-bold">
                        <td class="ps-3 py-2 text-uppercase text-muted" style="font-size:.65rem; letter-spacing:.4px;">Total</td>
                        @php
                            $grand_total = 0;
                            $cat_totals  = [];
                            foreach ($expense_categories as $cat) {
                                $cat_totals[$cat] = array_sum(array_map(fn($p) => $p[$cat] ?? 0, $expenses_pivot));
                                $grand_total += $cat_totals[$cat];
                            }
                        @endphp
                        @foreach($expense_categories as $cat)
                        <td class="text-end py-2 text-danger">{{ number_format($cat_totals[$cat], 2, ',', '.') }} €</td>
                        @endforeach
                        <td class="text-end pe-3 py-2 text-danger">{{ number_format($grand_total, 2, ',', '.') }} €</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endif

@if($accounts->isEmpty())
    <div class="text-center text-muted py-5">
        No hay cuentas activas. <a href="{{ route('accounts.index') }}">Añade una cuenta</a> para empezar.
    </div>
@else
<div class="card">
    <div class="card-body p-0">
        <table class="table table-sm mb-0" style="font-size:.75rem;">
            <thead style="background:#f8f9fa; position:sticky; top:0; z-index:1;">
                <tr class="text-uppercase text-muted" style="font-size:.62rem; letter-spacing:.4px;">
                    <th class="ps-3 py-2">Mes</th>
                    <th class="py-2">Cuenta</th>
                    <th class="text-end py-2">Inicio</th>
                    <th class="text-end py-2">Ingresos</th>
                    <th class="text-end py-2">Gastos</th>
                    <th class="text-end py-2">Transferencias</th>
                    <th class="text-end py-2">Ahorros</th>
                    <th class="text-end pe-3 py-2">Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($months as $i => $month)
                @if($month['has_data'])
                @php
                    $net_color  = $month['net'] >= 0 ? '#198754' : '#dc3545';
                    $accounts   = $month['accounts'];
                    $multi      = count($accounts) > 1;
                    $rowspan    = $multi ? count($accounts) + 1 : count($accounts);
                    $first      = true;
                @endphp
                @foreach($accounts as $acc_id => $acc)
                @php $closed = ($acc['status'] ?? 1) == 2; @endphp
                <tr {{ $closed ? 'style=color:#6c757d;' : '' }}>
                    @if($first)
                    <td class="ps-3 py-1 align-middle text-center" rowspan="{{ $rowspan }}" style="white-space:nowrap; background:#fafafa;">
                        <a href="{{ route('home.month', [$year, $i + 1]) }}" class="home-link fw-bold text-uppercase d-block mb-1" style="font-size:.7rem; letter-spacing:.4px;">{{ strtoupper($month['name_short']) }}</a>
                        <span class="fw-bold d-block" style="font-size:.65rem; color:{{ $net_color }};">
                            {{ $month['net'] >= 0 ? '+' : '' }}{{ number_format($month['net'], 2, ',', '.') }}€
                        </span>
                    </td>
                    @php $first = false; @endphp
                    @endif
                    <td class="py-1">
                        <a href="{{ route('home.month', [$year, $i + 1]) }}?account_id={{ $acc_id }}" class="home-link fw-semibold" {{ $closed ? 'style=color:#6c757d;' : '' }}>{{ $acc['name'] }}</a>
                        @if($closed)<span class="badge ms-1" style="font-size:.55rem; background:#495057; color:#fff; vertical-align:middle;">CERRADA</span>@endif
                    </td>
                    <td class="text-end py-1 text-muted">{{ number_format($acc['start'], 2, ',', '.') }} €</td>
                    <td class="text-end py-1 text-success fw-semibold">{{ $acc['income'] > 0 ? '+' : '' }}{{ number_format($acc['income'], 2, ',', '.') }} €</td>
                    <td class="text-end py-1 text-danger fw-semibold">{{ number_format($acc['expenses'], 2, ',', '.') }} €</td>
                    <td class="text-end py-1 text-muted">{{ $acc['transfers'] != 0 ? ($acc['transfers'] > 0 ? '+' : '') . number_format($acc['transfers'], 2, ',', '.') . ' €' : '—' }}</td>
                    <td class="text-end py-1 text-warning fw-semibold">{{ $acc['savings'] != 0 ? ($acc['savings'] > 0 ? '+' : '') . number_format($acc['savings'], 2, ',', '.') . ' €' : '—' }}</td>
                    <td class="text-end pe-3 py-1 fw-bold">{{ number_format($acc['balance'], 2, ',', '.') }} €</td>
                </tr>
                @endforeach
                @if($multi)
                <tr style="background:#f8f9fa;">
                    <td class="py-1 text-uppercase text-muted fw-bold" style="font-size:.65rem; letter-spacing:.4px;">Total</td>
                    <td class="text-end py-1 text-muted">{{ number_format($month['total']['start'], 2, ',', '.') }} €</td>
                    <td class="text-end py-1 text-success fw-bold">{{ $month['total']['income'] > 0 ? '+' : '' }}{{ number_format($month['total']['income'], 2, ',', '.') }} €</td>
                    <td class="text-end py-1 text-danger fw-bold">{{ number_format($month['total']['expenses'], 2, ',', '.') }} €</td>
                    <td class="text-end py-1 text-muted">{{ $month['total']['transfers'] != 0 ? ($month['total']['transfers'] > 0 ? '+' : '') . number_format($month['total']['transfers'], 2, ',', '.') . ' €' : '—' }}</td>
                    <td class="text-end py-1 text-warning fw-bold">{{ $month['total']['savings'] != 0 ? ($month['total']['savings'] > 0 ? '+' : '') . number_format($month['total']['savings'], 2, ',', '.') . ' €' : '—' }}</td>
                    <td class="text-end pe-3 py-1 fw-bold">{{ number_format($month['total']['balance'], 2, ',', '.') }} €</td>
                </tr>
                @endif
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@vite(['resources/js/pages/home-index.js'])
<style>
    .home-link { color: #212529; text-decoration: underline; }
    .home-link:hover { opacity: .7; }
</style>
