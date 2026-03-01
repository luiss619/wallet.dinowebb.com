<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Movement;
use App\Models\Service;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Movement::with('account', 'service', 'category', 'subcategory')
                ->where('movements.user_id', Auth::id());

            if ($s = $request->input('search')) {
                $query->where(function ($q) use ($s) {
                    $q->where('movements.description', 'like', "%{$s}%")
                      ->orWhereHas('account',  fn ($q) => $q->where('name', 'like', "%{$s}%"))
                      ->orWhereHas('service',  fn ($q) => $q->where('name', 'like', "%{$s}%"))
                      ->orWhereHas('category', fn ($q) => $q->where('name', 'like', "%{$s}%"));
                });
            }

            $sort = $request->input('sort');
            $dir  = $request->input('dir') === 'desc' ? 'desc' : 'asc';

            if ($sort === 'account') {
                $query->join('accounts', 'movements.account_id', '=', 'accounts.id')
                      ->select('movements.*')
                      ->orderBy('accounts.name', $dir);
            } elseif ($sort === 'service') {
                $query->leftJoin('services', 'movements.service_id', '=', 'services.id')
                      ->select('movements.*')
                      ->orderBy('services.name', $dir);
            } elseif ($sort === 'category') {
                $query->leftJoin('categories', 'movements.category_id', '=', 'categories.id')
                      ->select('movements.*')
                      ->orderBy('categories.name', $dir);
            } elseif (in_array($sort, ['id', 'date', 'quantity', 'status'])) {
                $query->orderBy("movements.{$sort}", $dir);
            } else {
                $query->orderBy('movements.date', 'desc');
            }

            $perPage = min(max((int) $request->input('per_page', 25), 1), 100);
            $items   = $query->paginate($perPage);

            return response()->json([
                'rows'       => view('movements._rows', ['movements' => $items])->render(),
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

        $accounts      = Account::where('user_id', Auth::id())->where('status', 1)->orderBy('name')->get();
        $services      = Service::where('status', 1)->orderBy('name')->get();
        $categories    = Category::where('status', 1)->orderBy('name')->get();
        $subcategories = Subcategory::where('status', 1)->orderBy('name')->get();

        return view('movements.index', compact('accounts', 'services', 'categories', 'subcategories'));
    }

    public function show(Movement $movement)
    {
        abort_if($movement->user_id !== Auth::id(), 403);
        return response()->json($movement->only(
            'id', 'account_id', 'service_id', 'category_id', 'subcategory_id',
            'quantity', 'date', 'description', 'status'
        ));
    }

    public function store(MovementRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        Movement::create($data);

        return back()->with('success', 'Movement created successfully.');
    }

    public function update(MovementRequest $request, Movement $movement)
    {
        abort_if($movement->user_id !== Auth::id(), 403);
        $movement->update($request->validated());

        return back()->with('success', 'Movement updated successfully.');
    }

    public function destroy(Movement $movement)
    {
        abort_if($movement->user_id !== Auth::id(), 403);
        $movement->delete();
        return back()->with('success', 'Movement deleted successfully.');
    }
}
