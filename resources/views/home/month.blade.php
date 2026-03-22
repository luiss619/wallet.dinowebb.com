@extends('layouts.vertical', ['title' => $monthName . ' ' . $year])

@section('content')
@php
    $totalIncome    = $income->sum('quantity');
    $totalExpenses  = $expenses->sum('quantity');
    $totalTransfers = $transfers->sum('quantity');
    $totalSavings   = $savings->sum('quantity');

    // Income grouped by service for chart
    $incomeByService = $income
        ->groupBy(fn($m) => $m->service->name ?? 'Sin servicio')
        ->map(fn($g) => round($g->sum('quantity'), 2))
        ->sortDesc();

    // Expenses grouped by category for chart (use absolute values)
    $expensesByCategory = $expenses
        ->groupBy(fn($m) => $m->service->category->name ?? 'Sin categoría')
        ->map(fn($g) => round(abs($g->sum('quantity')), 2))
        ->sortDesc();
@endphp

{{-- Header --}}
<div class="d-flex align-items-start justify-content-between mt-4 mb-4 flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm rounded-pill px-3">
            <i class="ti ti-arrow-left me-1"></i>Volver
        </a>
        <div>
            <h4 class="fw-bold mb-0">{{ $monthName }} {{ $year }}</h4>
            <small class="text-muted">{{ $accountName ?? 'Todas las cuentas' }}</small>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap justify-content-end">
        <span class="badge rounded-pill px-3 py-2 bg-success-subtle text-success" style="font-size:.8rem;">
            Ingresos&nbsp;&nbsp;<strong>+{{ number_format($totalIncome, 2, ',', '.') }} €</strong>
        </span>
        <span class="badge rounded-pill px-3 py-2 bg-danger-subtle text-danger" style="font-size:.8rem;">
            Gastos&nbsp;&nbsp;<strong>{{ number_format($totalExpenses, 2, ',', '.') }} €</strong>
        </span>
        @if($totalTransfers != 0)
        <span class="badge rounded-pill px-3 py-2 bg-primary-subtle text-primary" style="font-size:.8rem;">
            Transferencias&nbsp;&nbsp;<strong>{{ $totalTransfers >= 0 ? '+' : '' }}{{ number_format($totalTransfers, 2, ',', '.') }} €</strong>
        </span>
        @endif
        @if($totalSavings != 0)
        <span class="badge rounded-pill px-3 py-2 bg-warning-subtle text-warning" style="font-size:.8rem;">
            Ahorros&nbsp;&nbsp;<strong>+{{ number_format($totalSavings, 2, ',', '.') }} €</strong>
        </span>
        @endif
        <span class="badge rounded-pill px-3 py-2 {{ $totalBalance >= 0 ? 'bg-primary' : 'bg-danger' }} text-white" style="font-size:.8rem;">
            Balance&nbsp;&nbsp;<strong>{{ number_format($totalBalance, 2, ',', '.') }} €</strong>
        </span>
    </div>
</div>

{{-- Ingresos --}}
@if($income->count())
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between py-2" style="border-left:4px solid #198754;">
        <span class="fw-bold text-success"><i class="ti ti-trending-up me-2"></i>Ingresos</span>
        <span class="fw-bold text-success">+{{ number_format($totalIncome, 2, ',', '.') }} €</span>
    </div>
    <div class="card-body p-0">
        <div class="row g-0">
            {{-- Tabla --}}
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="text-uppercase text-muted" style="font-size:.65rem; letter-spacing:.5px; background:#f8f9fa;">
                            <tr>
                                <th class="ps-3">Fecha</th>
                                @if(!$accountId)<th>Cuenta</th>@endif
                                <th>Servicio</th>
                                <th>Descripción</th>
                                <th class="text-end pe-3">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($income as $m)
                            <tr>
                                <td class="ps-3 text-muted" style="font-size:.8rem; white-space:nowrap;">{{ \Carbon\Carbon::parse($m->date)->format('d/m') }}</td>
                                @if(!$accountId)<td class="text-muted" style="font-size:.8rem;">{{ $m->account->name ?? '-' }}</td>@endif
                                <td style="font-size:.8rem;">{{ $m->service->name ?? '-' }}</td>
                                <td style="font-size:.8rem;" class="text-muted">{{ $m->description ?: '-' }}</td>
                                <td class="text-end pe-3 text-success fw-semibold" style="font-size:.85rem; white-space:nowrap;">
                                    +{{ number_format($m->quantity, 2, ',', '.') }} €
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Donut chart --}}
            <div class="col-md-4 d-flex align-items-center justify-content-center border-start" style="min-height:220px;">
                <div id="incomeChart" style="width:100%; max-width:320px;"></div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Gastos --}}
@if($expenses->count())
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between py-2" style="border-left:4px solid #dc3545;">
        <span class="fw-bold text-danger"><i class="ti ti-trending-down me-2"></i>Gastos</span>
        <span class="fw-bold text-danger">{{ number_format($totalExpenses, 2, ',', '.') }} €</span>
    </div>
    <div class="card-body p-0">
        <div class="row g-0">
            {{-- Tabla --}}
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="text-uppercase text-muted" style="font-size:.65rem; letter-spacing:.5px; background:#f8f9fa;">
                            <tr>
                                <th class="ps-3">Fecha</th>
                                @if(!$accountId)<th>Cuenta</th>@endif
                                <th>Categoría</th>
                                <th>Servicio</th>
                                <th>Descripción</th>
                                <th class="text-end pe-3">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $m)
                            <tr>
                                <td class="ps-3 text-muted" style="font-size:.8rem; white-space:nowrap;">{{ \Carbon\Carbon::parse($m->date)->format('d/m') }}</td>
                                @if(!$accountId)<td class="text-muted" style="font-size:.8rem;">{{ $m->account->name ?? '-' }}</td>@endif
                                <td style="font-size:.75rem;" class="text-muted">{{ $m->service->category->name ?? '-' }}</td>
                                <td style="font-size:.8rem;">{{ $m->service->name ?? '-' }}</td>
                                <td style="font-size:.8rem;" class="text-muted">{{ $m->description ?: '-' }}</td>
                                <td class="text-end pe-3 text-danger fw-semibold" style="font-size:.85rem; white-space:nowrap;">
                                    {{ number_format($m->quantity, 2, ',', '.') }} €
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Donut chart --}}
            <div class="col-md-4 d-flex align-items-center justify-content-center border-start" style="min-height:220px;">
                <div id="expensesChart" style="width:100%; max-width:320px;"></div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Transferencias --}}
@if($transfers->count())
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between py-2" style="border-left:4px solid #0d6efd;">
        <span class="fw-bold text-primary"><i class="ti ti-arrows-exchange me-2"></i>Transferencias</span>
        <span class="fw-bold text-primary">{{ $totalTransfers >= 0 ? '+' : '' }}{{ number_format($totalTransfers, 2, ',', '.') }} €</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="text-uppercase text-muted" style="font-size:.65rem; letter-spacing:.5px; background:#f8f9fa;">
                    <tr>
                        <th class="ps-3">Fecha</th>
                        @if(!$accountId)<th>Cuenta</th>@endif
                        <th>Servicio</th>
                        <th>Descripción</th>
                        <th class="text-end pe-3">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transfers as $m)
                    <tr>
                        <td class="ps-3 text-muted" style="font-size:.8rem; white-space:nowrap;">{{ \Carbon\Carbon::parse($m->date)->format('d/m') }}</td>
                        @if(!$accountId)<td class="text-muted" style="font-size:.8rem;">{{ $m->account->name ?? '-' }}</td>@endif
                        <td style="font-size:.8rem;">{{ $m->service->name ?? '-' }}</td>
                        <td style="font-size:.8rem;" class="text-muted">{{ $m->description ?: '-' }}</td>
                        <td class="text-end pe-3 fw-semibold text-primary" style="font-size:.85rem; white-space:nowrap;">
                            {{ $m->quantity >= 0 ? '+' : '' }}{{ number_format($m->quantity, 2, ',', '.') }} €
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- Ahorros --}}
@if($savings->count())
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between py-2" style="border-left:4px solid #ffc107;">
        <span class="fw-bold text-warning"><i class="ti ti-piggy-bank me-2"></i>Ahorros</span>
        <span class="fw-bold text-warning">+{{ number_format($totalSavings, 2, ',', '.') }} €</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="text-uppercase text-muted" style="font-size:.65rem; letter-spacing:.5px; background:#f8f9fa;">
                    <tr>
                        <th class="ps-3">Fecha</th>
                        @if(!$accountId)<th>Cuenta</th>@endif
                        <th>Servicio</th>
                        <th>Descripción</th>
                        <th class="text-end pe-3">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($savings as $m)
                    <tr>
                        <td class="ps-3 text-muted" style="font-size:.8rem; white-space:nowrap;">{{ \Carbon\Carbon::parse($m->date)->format('d/m') }}</td>
                        @if(!$accountId)<td class="text-muted" style="font-size:.8rem;">{{ $m->account->name ?? '-' }}</td>@endif
                        <td style="font-size:.8rem;">{{ $m->service->name ?? '-' }}</td>
                        <td style="font-size:.8rem;" class="text-muted">{{ $m->description ?: '-' }}</td>
                        <td class="text-end pe-3 fw-semibold text-warning" style="font-size:.85rem; white-space:nowrap;">
                            +{{ number_format($m->quantity, 2, ',', '.') }} €
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($income->isEmpty() && $expenses->isEmpty() && $transfers->isEmpty() && $savings->isEmpty())
    <div class="text-center text-muted py-5">No hay movimientos para este período.</div>
@endif

@php
    function renderDonut(string $elId, $data, string $colorSet): string {
        $series = array_values($data->toArray());
        $labels = array_keys($data->toArray());
        $total  = array_sum($series);
        $colors = $colorSet === 'green'
            ? ['#198754','#20c997','#0dcaf0','#0d6efd','#6610f2','#6f42c1','#d63384','#fd7e14','#ffc107']
            : ['#dc3545','#fd7e14','#ffc107','#e91e63','#9c27b0','#673ab7','#3f51b5','#2196f3','#00bcd4'];
        $s = json_encode($series);
        $l = json_encode($labels);
        $c = json_encode($colors);
        $t = number_format($total, 2, '.', '');
        return <<<JS
        (function(){
            const series={$s}, labels={$l}, total={$t};
            new ApexCharts(document.getElementById('{$elId}'), {
                chart: { type:'donut', height:260, toolbar:{show:false} },
                series, labels,
                legend: { position:'bottom', fontSize:'11px',
                    formatter:(label,opts) => label+' ('+((opts.w.globals.series[opts.seriesIndex]/total)*100).toFixed(1)+'%)'
                },
                dataLabels: { enabled:false },
                tooltip: { y:{ formatter: val => val.toLocaleString('es-ES',{minimumFractionDigits:2})+' €' } },
                plotOptions: { pie:{ donut:{ size:'65%', labels:{ show:true, total:{ show:true, label:'Total',
                    formatter: () => total.toLocaleString('es-ES',{minimumFractionDigits:2})+' €'
                }}}}},
                colors: {$c},
            }).render();
        })();
        JS;
    }
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($income->count())
    {!! renderDonut('incomeChart', $incomeByService, 'green') !!}
    @endif
    @if($expenses->count())
    {!! renderDonut('expensesChart', $expensesByCategory, 'red') !!}
    @endif
});
</script>

@endsection
