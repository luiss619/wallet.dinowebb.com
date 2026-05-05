@extends('layouts.vertical', ['title' => $month_name . ' ' . $year])

@section('content')

<div class="d-flex align-items-start justify-content-between mt-4 mb-4 flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm rounded-pill px-3">
            <i class="ti ti-arrow-left me-1"></i>Volver
        </a>
        <div>
            <h4 class="fw-bold mb-0">{{ $month_name }} {{ $year }}</h4>
            <small class="text-muted">{{ $account_name ?? 'Todas las cuentas' }}</small>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap justify-content-end">
        <span class="badge rounded-pill px-3 py-2 bg-success-subtle text-success" style="font-size:.8rem;">
            Ingresos&nbsp;&nbsp;<strong>+{{ number_format($total_income, 2, ',', '.') }} €</strong>
        </span>
        <span class="badge rounded-pill px-3 py-2 bg-danger-subtle text-danger" style="font-size:.8rem;">
            Gastos&nbsp;&nbsp;<strong>{{ number_format($total_expenses, 2, ',', '.') }} €</strong>
        </span>
        @if($total_transfers != 0)
        <span class="badge rounded-pill px-3 py-2 bg-primary-subtle text-primary" style="font-size:.8rem;">
            Transferencias&nbsp;&nbsp;<strong>{{ $total_transfers >= 0 ? '+' : '' }}{{ number_format($total_transfers, 2, ',', '.') }} €</strong>
        </span>
        @endif
        @if($total_savings != 0)
        <span class="badge rounded-pill px-3 py-2 bg-warning-subtle text-warning" style="font-size:.8rem;">
            Ahorros&nbsp;&nbsp;<strong>+{{ number_format($total_savings, 2, ',', '.') }} €</strong>
        </span>
        @endif
        <span class="badge rounded-pill px-3 py-2 {{ $total_balance >= 0 ? 'bg-primary' : 'bg-danger' }} text-white" style="font-size:.8rem;">
            Balance&nbsp;&nbsp;<strong>{{ number_format($total_balance, 2, ',', '.') }} €</strong>
        </span>
    </div>
</div>

@if($income->count())
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between py-2" style="border-left:4px solid #198754;">
        <span class="fw-bold text-success"><i class="ti ti-trending-up me-2"></i>Ingresos</span>
        <span class="fw-bold text-success">+{{ number_format($total_income, 2, ',', '.') }} €</span>
    </div>
    <div class="card-body p-0">
        <div class="row g-0">
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="text-uppercase text-muted" style="font-size:.65rem; letter-spacing:.5px; background:#f8f9fa;">
                            <tr>
                                <th class="ps-3">Fecha</th>
                                @if(!$account_id)<th>Cuenta</th>@endif
                                <th>Servicio</th>
                                <th>Descripción</th>
                                <th class="text-end pe-3">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($income as $m)
                            <tr>
                                <td class="ps-3 text-muted" style="font-size:.8rem; white-space:nowrap;">{{ \Carbon\Carbon::parse($m->date)->format('d/m') }}</td>
                                @if(!$account_id)<td class="text-muted" style="font-size:.8rem;">{{ $m->account->name ?? '-' }}</td>@endif
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
            <div class="col-md-4 d-flex align-items-center justify-content-center border-start" style="min-height:220px;">
                <div id="incomeChart" data-chart="{{ json_encode($income_chart_data) }}" style="width:100%; max-width:320px;"></div>
            </div>
        </div>
    </div>
</div>
@endif

@if($expenses->count())
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between py-2" style="border-left:4px solid #dc3545;">
        <span class="fw-bold text-danger"><i class="ti ti-trending-down me-2"></i>Gastos</span>
        <span class="fw-bold text-danger">{{ number_format($total_expenses, 2, ',', '.') }} €</span>
    </div>
    <div class="card-body p-0">
        <div class="row g-0">
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="text-uppercase text-muted" style="font-size:.65rem; letter-spacing:.5px; background:#f8f9fa;">
                            <tr>
                                <th class="ps-3">Fecha</th>
                                @if(!$account_id)<th>Cuenta</th>@endif
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
                                @if(!$account_id)<td class="text-muted" style="font-size:.8rem;">{{ $m->account->name ?? '-' }}</td>@endif
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
            <div class="col-md-4 d-flex align-items-center justify-content-center border-start" style="min-height:220px;">
                <div id="expensesChart" data-chart="{{ json_encode($expenses_chart_data) }}" style="width:100%; max-width:320px;"></div>
            </div>
        </div>
    </div>
</div>
@endif

@if($transfers->count())
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between py-2" style="border-left:4px solid #0d6efd;">
        <span class="fw-bold text-primary"><i class="ti ti-arrows-exchange me-2"></i>Transferencias</span>
        <span class="fw-bold text-primary">{{ $total_transfers >= 0 ? '+' : '' }}{{ number_format($total_transfers, 2, ',', '.') }} €</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="text-uppercase text-muted" style="font-size:.65rem; letter-spacing:.5px; background:#f8f9fa;">
                    <tr>
                        <th class="ps-3">Fecha</th>
                        @if(!$account_id)<th>Cuenta</th>@endif
                        <th>Servicio</th>
                        <th>Descripción</th>
                        <th class="text-end pe-3">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transfers as $m)
                    <tr>
                        <td class="ps-3 text-muted" style="font-size:.8rem; white-space:nowrap;">{{ \Carbon\Carbon::parse($m->date)->format('d/m') }}</td>
                        @if(!$account_id)<td class="text-muted" style="font-size:.8rem;">{{ $m->account->name ?? '-' }}</td>@endif
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

@if($savings->count())
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between py-2" style="border-left:4px solid #ffc107;">
        <span class="fw-bold text-warning"><i class="ti ti-piggy-bank me-2"></i>Ahorros</span>
        <span class="fw-bold text-warning">+{{ number_format($total_savings, 2, ',', '.') }} €</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="text-uppercase text-muted" style="font-size:.65rem; letter-spacing:.5px; background:#f8f9fa;">
                    <tr>
                        <th class="ps-3">Fecha</th>
                        @if(!$account_id)<th>Cuenta</th>@endif
                        <th>Servicio</th>
                        <th>Descripción</th>
                        <th class="text-end pe-3">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($savings as $m)
                    <tr>
                        <td class="ps-3 text-muted" style="font-size:.8rem; white-space:nowrap;">{{ \Carbon\Carbon::parse($m->date)->format('d/m') }}</td>
                        @if(!$account_id)<td class="text-muted" style="font-size:.8rem;">{{ $m->account->name ?? '-' }}</td>@endif
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

@endsection

@section('scripts')
@vite(['resources/js/pages/home-month.js'])
@endsection
