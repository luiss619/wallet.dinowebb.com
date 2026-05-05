<div class="row">
    <div class="col-8 mb-3">
        <label class="form-label">Cuenta <span class="text-danger">*</span></label>
        <select name="account_id" class="form-select js-select2" required>
            <option value="">— Seleccionar cuenta —</option>
            @foreach($accounts as $acc)
                <option value="{{ $acc->id }}"
                    {{ old('account_id', $model?->account_id) == $acc->id ? 'selected' : '' }}>
                    {{ $acc->name }} ({{ $acc->currency }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-4 mb-3">
        <label class="form-label">Fecha <span class="text-danger">*</span></label>
        <input type="date" name="date" class="form-control" required
            value="{{ old('date', $model?->date?->format('Y-m-d') ?? date('Y-m-d')) }}" />
    </div>
</div>
<div class="row">
    <div class="col-8 mb-3">
        <label class="form-label">Importe <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">€</span>
            <input type="number" name="quantity" class="form-control" step="0.01" required
                placeholder="Positivo = ingreso · Negativo = gasto"
                value="{{ old('quantity', $model?->quantity) }}" />
        </div>
        <div class="form-text">Usa valores negativos para gastos (ej. -45.00)</div>
    </div>
    <div class="col-4 mb-3">
        <label class="form-label">Tipo <span class="text-danger">*</span></label>
        <select name="type" class="form-select js-select2" required>
            <option value="0" {{ old('type', $model?->type ?? 0) == 0 ? 'selected' : '' }}>Normal</option>
            <option value="1" {{ old('type', $model?->type ?? 0) == 1 ? 'selected' : '' }}>Transferencia</option>
            <option value="2" {{ old('type', $model?->type ?? 0) == 2 ? 'selected' : '' }}>Ahorro</option>
            <option value="3" {{ old('type', $model?->type ?? 0) == 3 ? 'selected' : '' }}>Paso</option>
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Servicio</label>
    @php
        $grouped = $services->groupBy(fn($s) => $s->category?->name ?? 'Sin categoría')->sortKeys();
    @endphp
    <select name="service_id" class="form-select js-select2">
        <option value="">— Ninguno —</option>
        @foreach($grouped as $catName => $svcs)
            <optgroup label="{{ $catName }}">
                @foreach($svcs->sortBy('name') as $svc)
                    <option value="{{ $svc->id }}"
                        {{ old('service_id', $model?->service_id) == $svc->id ? 'selected' : '' }}>
                        {{ $svc->name }}
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Descripción</label>
    <input type="text" name="description" class="form-control" maxlength="500"
        value="{{ old('description', $model?->description) }}" placeholder="Nota opcional..." />
</div>
<div class="mb-3">
    <label class="form-label">Estado <span class="text-danger">*</span></label>
    <select name="status" class="form-select js-select2" required>
        <option value="1" {{ old('status', $model?->status ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ old('status', $model?->status ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
    </select>
</div>
