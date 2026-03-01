<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::where('user_id', Auth::id());

        if ($s = $request->input('search')) {
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('bank', 'like', "%{$s}%");
            });
        }

        $sortable = ['name', 'bank', 'balance', 'currency', 'status'];
        $sort = in_array($request->input('sort'), $sortable) ? $request->input('sort') : 'name';
        $dir  = $request->input('dir') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sort, $dir);

        $perPage = min(max((int) $request->input('per_page', 25), 1), 100);
        $items   = $query->paginate($perPage);

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('accounts._rows', ['accounts' => $items])->render(),
                'pagination' => [
                    'total'        => $items->total(),
                    'per_page'     => $items->perPage(),
                    'current_page' => $items->currentPage(),
                    'last_page'    => $items->lastPage(),
                    'from'         => $items->firstItem(),
                    'to'           => $items->lastItem(),
                ],
            ]);
        }

        return view('accounts.index');
    }

    public function show(Account $account)
    {
        abort_if($account->user_id !== Auth::id(), 403);
        return response()->json($account->only('id', 'name', 'bank', 'account_number', 'balance', 'currency', 'status'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'bank'           => ['required', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'balance'        => ['required', 'numeric'],
            'currency'       => ['required', 'string', 'size:3'],
            'status'         => ['required', 'in:0,1'],
        ]);

        $data['user_id'] = Auth::id();
        Account::create($data);

        return back()->with('success', 'Account created successfully.');
    }

    public function update(Request $request, Account $account)
    {
        abort_if($account->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'bank'           => ['required', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'balance'        => ['required', 'numeric'],
            'currency'       => ['required', 'string', 'size:3'],
            'status'         => ['required', 'in:0,1'],
        ]);

        $account->update($data);

        return back()->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        abort_if($account->user_id !== Auth::id(), 403);
        $account->delete();
        return back()->with('success', 'Account deleted successfully.');
    }
}
