<div class="mb-3">
    <label class="form-label">Nombre <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required
        value="{{ old('name', $model?->name) }}" placeholder="ej. Cuenta principal" />
</div>
<div class="mb-3">
    <label class="form-label">Banco <span class="text-danger">*</span></label>
    <input type="text" name="bank" class="form-control" required
        value="{{ old('bank', $model?->bank) }}" placeholder="ej. Santander" />
</div>
<div class="mb-3">
    <label class="form-label">Número de cuenta</label>
    <input type="text" name="account_number" class="form-control"
        value="{{ old('account_number', $model?->account_number) }}" placeholder="IBAN o número de cuenta" />
</div>
<div class="row">
    <div class="col-8 mb-3">
        <label class="form-label">Saldo <span class="text-danger">*</span></label>
        <input type="number" name="balance" class="form-control" step="0.01" required
            value="{{ old('balance', $model?->balance ?? 0) }}" />
    </div>
    <div class="col-4 mb-3">
        <label class="form-label">Moneda <span class="text-danger">*</span></label>
        <select name="currency" class="form-select js-select2" required>
            @foreach(['EUR','USD','GBP','CHF'] as $cur)
                <option value="{{ $cur }}" {{ old('currency', $model?->currency ?? 'EUR') === $cur ? 'selected' : '' }}>
                    {{ $cur }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Estado <span class="text-danger">*</span></label>
    <select name="status" class="form-select js-select2" required>
        <option value="1" {{ old('status', $model?->status ?? 1) == 1 ? 'selected' : '' }}>Activa</option>
        <option value="0" {{ old('status', $model?->status ?? 1) == 0 ? 'selected' : '' }}>Inactiva</option>
        <option value="2" {{ old('status', $model?->status ?? 1) == 2 ? 'selected' : '' }}>Cerrada</option>
    </select>
</div>
