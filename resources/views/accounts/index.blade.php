@extends('layouts.vertical', ['title' => 'Cuentas'])

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card"
                data-ajax-table
                data-ajax-table-url="{{ route('accounts.index') }}"
                data-ajax-table-per-page="10"
                data-ajax-table-sort="id"
                data-ajax-table-dir="asc">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Cuentas</h4>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreateAccount">
                        <i class="ti ti-plus me-1"></i> Nueva cuenta
                    </button>
                </div>
                <div class="card-header border-0">
                    <div class="app-search" style="max-width: 300px;">
                        <input class="form-control" data-table-search placeholder="Buscar cuentas..." type="search" />
                        <i class="app-search-icon text-muted" data-lucide="search"></i>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom table-centered table-hover w-100 mb-0">
                        <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                <th data-table-sort="id" style="width:1%;">ID</th>
                                <th data-table-sort="name">Nombre</th>
                                <th data-table-sort="bank">Banco</th>
                                <th>Nº de cuenta</th>
                                <th data-table-sort="balance">Saldo</th>
                                <th>Moneda</th>
                                <th>Estado</th>
                                <th class="text-center" style="width: 1%;">Acciones</th>
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

    <!-- Modal Crear -->
    <div class="modal fade" id="modalCreateAccount" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva cuenta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('accounts.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @include('accounts._form', ['model' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear cuenta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar compartido -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar cuenta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        @include('accounts._form', ['model' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/ajax-table.js', 'resources/js/pages/modal-select2.js'])
@endsection
