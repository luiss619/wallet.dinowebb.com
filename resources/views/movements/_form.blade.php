<div class="row">
    <div class="col-8 mb-3">
        <label class="form-label">Account <span class="text-danger">*</span></label>
        <select name="account_id" class="form-select" required>
            <option value="">— Select account —</option>
            @foreach($accounts as $acc)
                <option value="{{ $acc->id }}"
                    {{ old('account_id', $model?->account_id) == $acc->id ? 'selected' : '' }}>
                    {{ $acc->name }} ({{ $acc->currency }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-4 mb-3">
        <label class="form-label">Date <span class="text-danger">*</span></label>
        <input type="date" name="date" class="form-control" required
            value="{{ old('date', $model?->date?->format('Y-m-d') ?? date('Y-m-d')) }}" />
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Amount <span class="text-danger">*</span></label>
    <div class="input-group">
        <span class="input-group-text">€</span>
        <input type="number" name="quantity" class="form-control" step="0.01" required
            placeholder="Positive = income · Negative = expense"
            value="{{ old('quantity', $model?->quantity) }}" />
    </div>
    <div class="form-text">Use negative values for expenses (e.g. -45.00)</div>
</div>
<div class="mb-3">
    <label class="form-label">Service</label>
    <select name="service_id" class="form-select">
        <option value="">— None —</option>
        @foreach($services as $svc)
            <option value="{{ $svc->id }}"
                {{ old('service_id', $model?->service_id) == $svc->id ? 'selected' : '' }}>
                {{ $svc->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <input type="text" name="description" class="form-control" maxlength="500"
        value="{{ old('description', $model?->description) }}" placeholder="Optional note..." />
</div>
<div class="mb-3">
    <label class="form-label">Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select" required>
        <option value="1" {{ old('status', $model?->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
        <option value="0" {{ old('status', $model?->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
