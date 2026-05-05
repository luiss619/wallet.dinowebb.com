<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
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

        $sortable = ['id', 'name', 'bank', 'balance', 'currency', 'status'];
        $sort = in_array($request->input('sort'), $sortable) ? $request->input('sort') : 'id';
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

    public function store(AccountRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        Account::create($data);

        return back()->with('success', 'Cuenta creada correctamente.');
    }

    public function update(AccountRequest $request, Account $account)
    {
        abort_if($account->user_id !== Auth::id(), 403);
        $account->update($request->validated());

        return back()->with('success', 'Cuenta actualizada correctamente.');
    }

    public function destroy(Account $account)
    {
        abort_if($account->user_id !== Auth::id(), 403);
        $account->delete();
        return back()->with('success', 'Cuenta eliminada correctamente.');
    }
}
