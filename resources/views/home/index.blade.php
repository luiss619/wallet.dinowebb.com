@extends('layouts.vertical', ['title' => 'Dashboard'])

@section('content')
@php
    // Find latest month with actual data
    $latestMonth = null;
    foreach ($months as $m) {
        if ($m['total']['income'] != 0 || $m['total']['expenses'] != 0 || $m['total']['transfers'] != 0) {
            $latestMonth = $m;
        }
    }

    $totalAssets      = $latestMonth ? $latestMonth['total']['balance'] : 0;
    $latestNet        = $latestMonth ? ($latestMonth['total']['income'] + $latestMonth['total']['expenses'] + $latestMonth['total']['transfers'] - $latestMonth['total']['savings']) : 0;
    $latestStart      = $latestMonth ? $latestMonth['total']['start'] : 0;
    $monthlyGrowthPct = ($latestStart != 0) ? round($latestNet / abs($latestStart) * 100, 1) : 0;
    $savingsRate      = ($yearlyIncome > 0) ? max(0, min(100, round($yearlyNet / $yearlyIncome * 100))) : 0;

    function fmtShort($n) {
        return ($n < 0 ? '-' : '') . number_format(abs($n), 2, ',', '.') . '€';
    }
@endphp

{{-- Header --}}
<div class="text-center mt-4 mb-2">
    <div class="d-flex justify-content-center gap-2">
        @foreach(array_reverse($yearRange) as $y)
            <a href="?year={{ $y }}"
               class="btn btn-sm rounded-pill px-3 {{ $y == $year ? 'btn-primary' : 'btn-light' }}">
                {{ $y }}
            </a>
        @endforeach
    </div>
</div>

{{-- Annual Overview --}}
<div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="fw-bold mb-0">Annual Overview</h5>
    <span class="badge bg-light text-muted fw-semibold text-uppercase rounded-pill px-3 py-2" style="font-size:.65rem; letter-spacing:.5px;">Rendimiento Acumulado</span>
</div>
<div class="row g-2 mb-2">
    @php $cumulativeSavingsTop = 0; @endphp
    @foreach($months as $i => $month)
    @php
        $hasData   = $month['total']['income'] != 0 || $month['total']['expenses'] != 0 || $month['total']['transfers'] != 0 || $month['total']['savings'] != 0;
        $mNet      = $month['total']['income'] + $month['total']['expenses'] + $month['total']['transfers'] - $month['total']['savings'];
        $mesEs     = ['January'=>'Enero','February'=>'Febrero','March'=>'Marzo','April'=>'Abril','May'=>'Mayo','June'=>'Junio','July'=>'Julio','August'=>'Agosto','September'=>'Septiembre','October'=>'Octubre','November'=>'Noviembre','December'=>'Diciembre'];
        $mesNombre = strtoupper(substr($mesEs[$month['name']] ?? $month['name'], 0, 3));
        $cumulativeSavingsTop += $month['total']['savings'];
    @endphp
    <div class="col-6 col-sm-4 col-md-2">
        @if($hasData)
        <a href="{{ route('home.month', [$year, $i + 1]) }}" class="text-decoration-none">
        @endif
        <div class="card mb-0 {{ !$hasData ? 'border-dashed opacity-60' : '' }}" style="transition: box-shadow .2s, transform .2s; cursor: {{ $hasData ? 'pointer' : 'default' }};"
             onmouseenter="{{ $hasData ? "this.style.boxShadow='0 6px 20px rgba(0,0,0,.12)'; this.style.transform='translateY(-2px)';" : '' }}"
             onmouseleave="{{ $hasData ? "this.style.boxShadow=''; this.style.transform='';" : '' }}">
            <div class="card-body p-2">
                <div class="text-uppercase fw-bold text-dark mb-1" style="font-size:.65rem;">
                    {{ $mesNombre }}
                </div>
                @if($hasData)
                    <div style="font-size:.7rem;" class="text-muted d-flex justify-content-between">Ingreso <span class="text-success fw-semibold">+{{ fmtShort($month['total']['income']) }}</span></div>
                    <div style="font-size:.7rem;" class="text-muted d-flex justify-content-between">Gasto <span class="text-danger fw-semibold">{{ fmtShort($month['total']['expenses']) }}</span></div>
                    <div style="font-size:.7rem;" class="text-muted d-flex justify-content-between">
                        Ahorro
                        <span class="{{ $month['total']['savings'] != 0 ? 'text-warning fw-semibold' : 'text-muted' }}">{{ fmtShort($month['total']['savings']) }}</span>
                    </div>
                    <div style="font-size:.7rem;" class="text-muted d-flex justify-content-between">
                        Restante
                        <span class="{{ $cumulativeSavingsTop != 0 ? 'text-warning fw-semibold' : 'text-muted' }}">{{ fmtShort($cumulativeSavingsTop) }}</span>
                    </div>
                    <div class="fw-bold mt-1 d-flex justify-content-between" style="font-size:.72rem;">
                        <span class="text-dark">Balance</span>
                        <span class="{{ $month['total']['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($month['total']['balance'], 2, ',', '.') }}€
                        </span>
                    </div>
                @else
                    <div class="text-muted mt-2" style="font-size:.7rem;">Proyectado</div>
                @endif
            </div>
        </div>
        @if($hasData)
        </a>
        @endif
    </div>
    @endforeach
</div>

{{-- Total Assets + Quick Insights --}}
<div class="row g-3 mb-4">
    <div class="col-md-7">
        <div class="card h-100 border-0" style="background: linear-gradient(135deg, #1a3a6e 0%, #2d5aa0 100%); color:#fff;">
            <div class="card-body p-4">
                <p class="text-uppercase mb-1 fw-semibold" style="font-size:.65rem; opacity:.75; letter-spacing:1px;">
                    Total Assets Under Management
                </p>
                <h2 class="fw-bold mb-4" style="font-size:2rem;">
                    €{{ number_format($totalAssets, 2, ',', '.') }}
                </h2>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge rounded-pill px-3 py-2" style="background:rgba(255,255,255,.2); font-size:.75rem;">
                        Monthly Growth&nbsp;&nbsp;<strong>{{ $monthlyGrowthPct >= 0 ? '+' : '' }}{{ $monthlyGrowthPct }}%</strong>
                    </span>
                    @if($latestMonth)
                    <span class="badge rounded-pill px-3 py-2" style="background:rgba(255,255,255,.15); font-size:.75rem;">
                        {{ $latestMonth['name'] }} Net&nbsp;&nbsp;<strong>{{ $latestNet >= 0 ? '+' : '' }}€{{ number_format($latestNet, 0, ',', '.') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-uppercase mb-3" style="font-size:.65rem; letter-spacing:1px;">Quick Insights</h6>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Savings Rate</small>
                        <small class="fw-semibold">{{ $savingsRate }}%</small>
                    </div>
                    <div class="progress" style="height:6px;">
                        <div class="progress-bar" style="width:{{ $savingsRate }}%;"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Risk Profile</small>
                        <span class="badge bg-primary rounded-pill px-3" style="font-size:.7rem;">Conservative Growth</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Projected {{ $year }}</small>
                    <small class="fw-bold {{ $yearlyNet >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $yearlyNet >= 0 ? '+' : '' }}€{{ number_format($yearlyNet, 2, ',', '.') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Monthly Financials --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Monthly Financials</h5>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-light"><i class="ti ti-filter me-1"></i>Filter</button>
        <button class="btn btn-sm btn-primary"><i class="ti ti-download me-1"></i>Export Report</button>
    </div>
</div>

@if($accounts->isEmpty())
    <div class="text-center text-muted py-5">
        No active accounts found. <a href="{{ route('accounts.index') }}">Add an account</a> to get started.
    </div>
@else
    @php $cumulativeSavings = 0; @endphp
    @foreach($months as $i => $month)
    @php
        $hasData = $month['total']['income'] != 0 || $month['total']['expenses'] != 0 || $month['total']['transfers'] != 0 || $month['total']['savings'] != 0;
        $mNet    = $month['total']['balance'];
        $quarter = 'Fiscal Quarter ' . ceil(($i + 1) / 3);
        $cumulativeSavings += $month['total']['savings'];
    @endphp
    @if($hasData)
    <div class="card mb-3">
        <div class="card-body">
            {{-- Month header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:4px; height:32px; background:#2d5aa0; border-radius:2px; flex-shrink:0;"></div>
                    <div>
                        <a href="{{ route('home.month', [$year, $i + 1]) }}" class="text-decoration-none text-dark">
                            <h6 class="fw-bold mb-0">{{ $month['name'] }} {{ $year }} <i class="ti ti-external-link" style="font-size:.7rem; opacity:.5;"></i></h6>
                        </a>
                        <small class="text-muted">{{ $quarter }}</small>
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center flex-wrap justify-content-end">
                    @if($cumulativeSavings != 0)
                    <span class="badge rounded-pill px-3 py-2 bg-warning-subtle text-warning" style="font-size:.75rem;">
                        Ahorro Acumulado: {{ number_format($cumulativeSavings, 0, ',', '.') }} €
                    </span>
                    @endif
                    <span class="badge rounded-pill px-3 py-2 {{ $mNet >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}"
                          style="font-size:.75rem;">
                        {{ $mNet >= 0 ? 'SURPLUS' : 'DEFICIT' }}:
                        {{ number_format($mNet, 2, ',', '.') }} €
                    </span>
                </div>
            </div>

            {{-- Accounts table --}}
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr class="text-uppercase text-muted" style="font-size:.65rem; letter-spacing:.5px;">
                            <th>Account</th>
                            <th class="text-end">Start</th>
                            <th class="text-end">Income</th>
                            <th class="text-end">Expenses</th>
                            <th class="text-end">Transfers</th>
                            <th class="text-end">Savings</th>
                            <th class="text-end">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($month['accounts'] as $accId => $acc)
                        <tr>
                            <td class="text-primary fw-semibold">
                                <a href="{{ route('home.month', [$year, $i + 1]) }}?account_id={{ $accId }}" class="text-decoration-none">
                                    {{ $acc['name'] }}
                                </a>
                            </td>
                            <td class="text-end">{{ number_format($acc['start'], 2, ',', '.') }} €</td>
                            <td class="text-end text-success fw-semibold">
                                {{ $acc['income'] > 0 ? '+' : '' }}{{ number_format($acc['income'], 2, ',', '.') }} €
                            </td>
                            <td class="text-end text-danger fw-semibold">
                                {{ number_format($acc['expenses'], 2, ',', '.') }} €
                            </td>
                            <td class="text-end text-muted">
                                {{ $acc['transfers'] != 0 ? ($acc['transfers'] > 0 ? '+' : '') . number_format($acc['transfers'], 2, ',', '.') . ' €' : '-' }}
                            </td>
                            <td class="text-end text-warning fw-semibold">
                                {{ $acc['savings'] != 0 ? ($acc['savings'] > 0 ? '+' : '') . number_format($acc['savings'], 2, ',', '.') . ' €' : '-' }}
                            </td>
                            <td class="text-end fw-bold">
                                {{ number_format($acc['balance'], 2, ',', '.') }} €
                            </td>
                        </tr>
                        @endforeach
                        @php $t = $month['total']; @endphp
                        <tr class="border-top fw-bold" style="background:#f8f9fa; font-size:.8rem;">
                            <td class="text-uppercase text-muted" style="letter-spacing:.5px;">Total</td>
                            <td class="text-end">{{ number_format($t['start'], 2, ',', '.') }} €</td>
                            <td class="text-end text-success">{{ $t['income'] > 0 ? '+' : '' }}{{ number_format($t['income'], 2, ',', '.') }} €</td>
                            <td class="text-end text-danger">{{ number_format($t['expenses'], 2, ',', '.') }} €</td>
                            <td class="text-end text-muted">{{ $t['transfers'] != 0 ? ($t['transfers'] > 0 ? '+' : '') . number_format($t['transfers'], 2, ',', '.') . ' €' : '-' }}</td>
                            <td class="text-end text-warning">{{ $t['savings'] != 0 ? ($t['savings'] > 0 ? '+' : '') . number_format($t['savings'], 2, ',', '.') . ' €' : '-' }}</td>
                            <td class="text-end">{{ number_format($t['balance'], 2, ',', '.') }} €</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
    @endforeach
@endif

@endsection
