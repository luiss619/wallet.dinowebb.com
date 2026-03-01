@forelse($services as $service)
    <tr>
        <td class="text-muted fs-xs">{{ $service->id }}</td>
        <td class="fw-medium">{{ $service->name }}</td>
        <td>{{ $service->category?->name ?? '—' }}</td>
        <td>{{ $service->subcategory?->name ?? '—' }}</td>
        <td>
            @if($service->status)
                <span class="badge badge-soft-success fs-xxs">Active</span>
            @else
                <span class="badge badge-soft-danger fs-xxs">Inactive</span>
            @endif
        </td>
        <td>
            <div class="d-flex justify-content-center gap-1">
                <button class="btn btn-light btn-icon btn-sm rounded-circle"
                    data-edit-url="{{ route('services.show', $service) }}"
                    data-update-url="{{ route('services.update', $service) }}"
                    title="Edit">
                    <i class="ti ti-edit fs-lg"></i>
                </button>
                <form action="{{ route('services.destroy', $service) }}" method="POST"
                    onsubmit="return confirm('Delete this service?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-light btn-icon btn-sm rounded-circle" title="Delete">
                        <i class="ti ti-trash fs-lg"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted py-4">No services found.</td>
    </tr>
@endforelse
