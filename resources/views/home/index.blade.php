@extends('layouts.vertical', ['title' => 'Dashboard'])

@section('content')
    @include('layouts.partials/page-title', ['title' => 'Dashboard ' . $year])

    {{-- Year selector --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <span class="text-muted fw-semibold me-1">Year:</span>
                @foreach($yearRange as $y)
                    <a href="{{ route('root') }}?year={{ $y }}"
                        class="btn btn-sm {{ $y == $year ? 'btn-primary' : 'btn-light' }}">
                        {{ $y }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fs-sm">Total Income {{ $year }}</p>
                            <h4 class="text-success mb-0">{{ number_format($yearlyIncome, 2, ',', '.') }} €</h4>
                        </div>
                        <span class="avatar-title bg-success-subtle text-success rounded-circle fs-24 avatar">
                            <i class="ti ti-trending-up"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fs-sm">Total Expenses {{ $year }}</p>
                            <h4 class="text-danger mb-0">{{ number_format($yearlyExpenses, 2, ',', '.') }} €</h4>
                        </div>
                        <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-24 avatar">
                            <i class="ti ti-trending-down"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fs-sm">Net {{ $year }}</p>
                            <h4 class="mb-0 {{ $yearlyNet >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($yearlyNet, 2, ',', '.') }} €
                            </h4>
                        </div>
                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-24 avatar">
                            <i class="ti ti-wallet"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Monthly summary table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Monthly Summary</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom table-hover table-centered mb-0">
                        <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                <th style="width: 110px;">Month</th>
                                <th>Account</th>
                                <th class="text-end">Start</th>
                                <th class="text-end">Income</th>
                                <th class="text-end">Expenses</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($months as $month)
                                @if($accounts->isEmpty())
                                    @break
                                @endif

                                @foreach($month['accounts'] as $accId => $acc)
                                    <tr>
                                        <td class="text-muted fs-xs fw-semibold">
                                            {{ $loop->first ? $month['name'] : '' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-info">{{ $acc['name'] }}</span>
                                        </td>
                                        <td class="text-end">{{ number_format($acc['start'], 2, ',', '.') }} €</td>
                                        <td class="text-end text-success fw-semibold">
                                            {{ $acc['income'] > 0 ? '+' : '' }}{{ number_format($acc['income'], 2, ',', '.') }} €
                                        </td>
                                        <td class="text-end text-danger fw-semibold">
                                            {{ number_format($acc['expenses'], 2, ',', '.') }} €
                                        </td>
                                        <td class="text-end fw-semibold {{ $acc['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($acc['balance'], 2, ',', '.') }} €
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- TOTAL row --}}
                                <tr class="table-active fw-bold">
                                    <td class="text-muted fs-xs fw-semibold"></td>
                                    <td><span class="badge bg-secondary">TOTAL</span></td>
                                    <td class="text-end">{{ number_format($month['total']['start'], 2, ',', '.') }} €</td>
                                    <td class="text-end text-success">
                                        {{ $month['total']['income'] > 0 ? '+' : '' }}{{ number_format($month['total']['income'], 2, ',', '.') }} €
                                    </td>
                                    <td class="text-end text-danger">
                                        {{ number_format($month['total']['expenses'], 2, ',', '.') }} €
                                    </td>
                                    <td class="text-end {{ $month['total']['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($month['total']['balance'], 2, ',', '.') }} €
                                    </td>
                                </tr>

                                {{-- Month separator --}}
                                <tr style="height: 3px; background: var(--bs-border-color);">
                                    <td colspan="6" class="p-0"></td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No data available.</td>
                                </tr>
                            @endforelse

                            @if($accounts->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No active accounts found. <a href="{{ route('accounts.index') }}">Add an account</a> to get started.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
