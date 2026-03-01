@extends('layouts.vertical', ['title' => 'Accounts'])

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card"
                data-ajax-table
                data-ajax-table-url="{{ route('accounts.index') }}"
                data-ajax-table-per-page="10">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Accounts</h4>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreateAccount">
                        <i class="ti ti-plus me-1"></i> New Account
                    </button>
                </div>
                <div class="card-header border-0">
                    <div class="app-search" style="max-width: 300px;">
                        <input class="form-control" data-table-search placeholder="Search accounts..." type="search" />
                        <i class="app-search-icon text-muted" data-lucide="search"></i>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom table-centered table-hover w-100 mb-0">
                        <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                <th data-table-sort="id" style="width:1%;">ID</th>
                                <th data-table-sort="name">Name</th>
                                <th data-table-sort="bank">Bank</th>
                                <th>Account Number</th>
                                <th data-table-sort="balance">Balance</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th class="text-center" style="width: 1%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center py-2">
                    <small data-table-pagination-info="accounts"></small>
                    <div data-table-pagination></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="modalCreateAccount" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('accounts.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @include('accounts._form', ['model' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Shared Edit Modal -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        @include('accounts._form', ['model' => null])
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
    @vite(['resources/js/pages/ajax-table.js'])
@endsection
