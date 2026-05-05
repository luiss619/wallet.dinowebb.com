@extends('layouts.vertical', ['title' => 'Dashboard'])

@section('content')

<div class="text-center mt-4 mb-2">
    <div class="d-flex justify-content-center gap-2">
        @foreach(array_reverse($year_range) as $y)
            <a href="?year={{ $y }}"
               class="btn btn-sm rounded-pill px-3 {{ $y == $year ? 'btn-primary' : 'btn-light' }}">
                {{ $y }}
            </a>
        @endforeach
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="fw-bold mb-0">Resumen Anual</h5>
    <span class="badge bg-light text-muted fw-semibold text-uppercase rounded-pill px-3 py-2" style="font-size:.65rem; letter-spacing:.5px;">Rendimiento Acumulado</span>
</div>
<div class="row g-2 mb-2">
    @foreach($months as $i => $month)
    <div class="col-6 col-sm-4 col-md-2">
        @if($month['has_data'])
        <a href="{{ route('home.month', [$year, $i + 1]) }}" class="text-decoration-none">
        @endif
        <div class="card mb-0 {{ !$month['has_data'] ? 'border-dashed opacity-60' : '' }}"
             style="transition: box-shadow .2s, transform .2s; cursor: {{ $month['has_data'] ? 'pointer' : 'default' }};"
             onmouseenter="{{ $month['has_data'] ? "this.style.boxShadow='0 6px 20px rgba(0,0,0,.12)'; this.style.transform='translateY(-2px)';" : '' }}"
             onmouseleave="{{ $month['has_data'] ? "this.style.boxShadow=''; this.style.transform='';" : '' }}">
            <div class="card-body p-2">
                <div class="text-uppercase fw-bold text-dark mb-1" style="font-size:.65rem;">
                    {{ $month['name_short'] }}
                </div>
                @if($month['has_data'])
                    <div style="font-size:.7rem;" class="text-muted d-flex justify-content-between">Ingreso <span class="text-success fw-semibold">+{{ number_format($month['total']['income'], 2, ',', '.') }}€</span></div>
                    <div style="font-size:.7rem;" class="text-muted d-flex justify-content-between">Gasto <span class="text-danger fw-semibold">{{ number_format($month['total']['expenses'], 2, ',', '.') }}€</span></div>
                    <div style="font-size:.7rem;" class="text-muted d-flex justify-content-between">
                        Ahorro
                        <span class="{{ $month['total']['savings'] != 0 ? 'text-warning fw-semibold' : 'text-muted' }}">{{ number_format($month['total']['savings'], 2, ',', '.') }}€</span>
                    </div>
                    <div style="font-size:.7rem;" class="text-muted d-flex justify-content-between">
                        Restante
                        <span class="{{ $month['cumulative_savings'] != 0 ? 'text-warning fw-semibold' : 'text-muted' }}">{{ number_format($month['cumulative_savings'], 2, ',', '.') }}€</span>
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
        @if($month['has_data'])
        </a>
        @endif
    </div>
    @endforeach
</div>

<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <h5 class="fw-bold mb-0">Finanzas Mensuales</h5>
</div>

@if($accounts->isEmpty())
    <div class="text-center text-muted py-5">
        No hay cuentas activas. <a href="{{ route('accounts.index') }}">Añade una cuenta</a> para empezar.
    </div>
@else
    @foreach($months as $i => $month)
    @if($month['has_data'])
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:4px; height:32px; background:#2d5aa0; border-radius:2px; flex-shrink:0;"></div>
                    <div>
                        <a href="{{ route('home.month', [$year, $i + 1]) }}" class="text-decoration-none text-dark">
                            <h6 class="fw-bold mb-0">{{ $month['name'] }} {{ $year }} <i class="ti ti-external-link" style="font-size:.7rem; opacity:.5;"></i></h6>
                        </a>
                        <small class="text-muted">{{ $month['quarter'] }}</small>
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center flex-wrap justify-content-end">
                    @if($month['cumulative_savings'] != 0)
                    <span class="badge rounded-pill px-3 py-2 bg-warning-subtle text-warning" style="font-size:.75rem;">
                        Ahorro Acumulado: {{ number_format($month['cumulative_savings'], 0, ',', '.') }} €
                    </span>
                    @endif
                    <span class="badge rounded-pill px-3 py-2 {{ $month['net'] >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}"
                          style="font-size:.75rem;">
                        {{ $month['net'] >= 0 ? 'SUPERÁVIT' : 'DÉFICIT' }}:
                        {{ number_format($month['net'], 2, ',', '.') }} €
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr class="text-uppercase text-muted" style="font-size:.65rem; letter-spacing:.5px;">
                            <th>Cuenta</th>
                            <th class="text-end">Inicio</th>
                            <th class="text-end">Ingresos</th>
                            <th class="text-end">Gastos</th>
                            <th class="text-end">Transferencias</th>
                            <th class="text-end">Ahorros</th>
                            <th class="text-end">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($month['accounts'] as $acc_id => $acc)
                        <tr>
                            <td class="text-primary fw-semibold">
                                <a href="{{ route('home.month', [$year, $i + 1]) }}?account_id={{ $acc_id }}" class="text-decoration-none">
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
                        <tr class="border-top fw-bold" style="background:#f8f9fa; font-size:.8rem;">
                            <td class="text-uppercase text-muted" style="letter-spacing:.5px;">TOTAL</td>
                            <td class="text-end">{{ number_format($month['total']['start'], 2, ',', '.') }} €</td>
                            <td class="text-end text-success">{{ $month['total']['income'] > 0 ? '+' : '' }}{{ number_format($month['total']['income'], 2, ',', '.') }} €</td>
                            <td class="text-end text-danger">{{ number_format($month['total']['expenses'], 2, ',', '.') }} €</td>
                            <td class="text-end text-muted">{{ $month['total']['transfers'] != 0 ? ($month['total']['transfers'] > 0 ? '+' : '') . number_format($month['total']['transfers'], 2, ',', '.') . ' €' : '-' }}</td>
                            <td class="text-end text-warning">{{ $month['total']['savings'] != 0 ? ($month['total']['savings'] > 0 ? '+' : '') . number_format($month['total']['savings'], 2, ',', '.') . ' €' : '-' }}</td>
                            <td class="text-end">{{ number_format($month['total']['balance'], 2, ',', '.') }} €</td>
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
