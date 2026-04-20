@forelse($accounts as $account)
    <tr>
        <td class="text-muted fs-xs">{{ $account->id }}</td>
        <td class="fw-medium">{{ $account->name }}</td>
        <td>{{ $account->bank }}</td>
        <td>{{ $account->account_number ?? '—' }}</td>
        <td>{{ number_format($account->balance, 2) }}</td>
        <td>{{ $account->currency }}</td>
        <td>
            @if($account->status)
                <span class="badge badge-soft-success fs-xxs">Active</span>
            @else
                <span class="badge badge-soft-danger fs-xxs">Inactive</span>
            @endif
        </td>
        <td>
            <div class="d-flex justify-content-center gap-1">
                <button class="btn btn-light btn-icon btn-sm rounded-circle"
                    data-edit-url="{{ route('accounts.show', $account) }}"
                    data-update-url="{{ route('accounts.update', $account) }}"
                    title="Edit">
                    <i class="ti ti-edit fs-lg"></i>
                </button>
                <form action="{{ route('accounts.destroy', $account) }}" method="POST"
                    data-delete-form>
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
        <td colspan="8" class="text-center text-muted py-4">No accounts found.</td>
    </tr>
@endforelse
