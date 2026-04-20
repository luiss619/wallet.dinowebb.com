@extends('layouts.vertical', ['title' => 'Movements'])

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card"
                data-ajax-table
                data-ajax-table-url="{{ route('movements.index') }}"
                data-ajax-table-per-page="10">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Movements</h4>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
                        <i class="ti ti-plus me-1"></i> New Movement
                    </button>
                </div>
                <div class="card-header border-0">
                    <div class="d-flex flex-wrap gap-2 align-items-end">
                        <div class="app-search" style="max-width: 240px;">
                            <input class="form-control" data-table-search placeholder="Search..." type="search" />
                            <i class="app-search-icon text-muted" data-lucide="search"></i>
                        </div>
                        <div>
                            <label class="form-label mb-1" style="font-size:.7rem;">Desde</label>
                            <input type="date" class="form-control form-control-sm" data-table-filter="date_from" style="min-width:130px;">
                        </div>
                        <div>
                            <label class="form-label mb-1" style="font-size:.7rem;">Hasta</label>
                            <input type="date" class="form-control form-control-sm" data-table-filter="date_to" style="min-width:130px;">
                        </div>
                        <div>
                            <label class="form-label mb-1" style="font-size:.7rem;">Cuenta</label>
                            <select class="form-select form-select-sm" data-table-filter="account_id" style="min-width:140px;">
                                <option value="">Todas</option>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom table-centered table-hover w-100 mb-0">
                        <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                <th data-table-sort="id" style="width:1%;">ID</th>
                                <th data-table-sort="date">Date</th>
                                <th data-table-sort="quantity">Amount</th>
                                <th>Type</th>
                                <th data-table-sort="account">Account</th>
                                <th data-table-sort="service">Service</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th class="text-center" style="width: 1%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center py-2">
                    <small data-table-pagination-info="movements"></small>
                    <div data-table-pagination></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="modalCreate" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Movement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('movements.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @include('movements._form', [
                            'model'         => null,
                            'accounts'      => $accounts,
                            'services'      => $services,
                        ])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Shared Edit Modal -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Movement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        @include('movements._form', [
                            'model'         => null,
                            'accounts'      => $accounts,
                            'services'      => $services,
                        ])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/ajax-table.js', 'resources/js/pages/modal-select2.js'])
@endsection
