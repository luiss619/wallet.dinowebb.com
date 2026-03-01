<div class="mb-3">
    <label class="form-label">Category <span class="text-danger">*</span></label>
    <select name="category_id" class="form-select" required>
        <option value="">— Select category —</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ old('category_id', $model?->category_id) == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required
        value="{{ old('name', $model?->name) }}" placeholder="Subcategory name" />
</div>
<div class="mb-3">
    <label class="form-label">Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select" required>
        <option value="1" {{ old('status', $model?->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
        <option value="0" {{ old('status', $model?->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
