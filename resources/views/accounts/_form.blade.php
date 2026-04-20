<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required
        value="{{ old('name', $model?->name) }}" placeholder="e.g. Main Checking" />
</div>
<div class="mb-3">
    <label class="form-label">Bank <span class="text-danger">*</span></label>
    <input type="text" name="bank" class="form-control" required
        value="{{ old('bank', $model?->bank) }}" placeholder="e.g. Santander" />
</div>
<div class="mb-3">
    <label class="form-label">Account Number</label>
    <input type="text" name="account_number" class="form-control"
        value="{{ old('account_number', $model?->account_number) }}" placeholder="IBAN or account number" />
</div>
<div class="row">
    <div class="col-8 mb-3">
        <label class="form-label">Balance <span class="text-danger">*</span></label>
        <input type="number" name="balance" class="form-control" step="0.01" required
            value="{{ old('balance', $model?->balance ?? 0) }}" />
    </div>
    <div class="col-4 mb-3">
        <label class="form-label">Currency <span class="text-danger">*</span></label>
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
    <label class="form-label">Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select js-select2" required>
        <option value="1" {{ old('status', $model?->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
        <option value="0" {{ old('status', $model?->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
