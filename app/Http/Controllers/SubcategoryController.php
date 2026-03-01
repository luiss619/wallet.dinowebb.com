<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Subcategory::with('category');

            if ($s = $request->input('search')) {
                $query->where('subcategories.name', 'like', "%{$s}%")
                      ->orWhereHas('category', fn ($q) => $q->where('name', 'like', "%{$s}%"));
            }

            $sort = $request->input('sort');
            $dir  = $request->input('dir') === 'desc' ? 'desc' : 'asc';

            if ($sort === 'category') {
                $query->join('categories', 'subcategories.category_id', '=', 'categories.id')
                      ->select('subcategories.*')
                      ->orderBy('categories.name', $dir);
            } elseif (in_array($sort, ['name', 'status'])) {
                $query->orderBy($sort, $dir);
            } else {
                $query->orderBy('subcategories.name');
            }

            $perPage = min(max((int) $request->input('per_page', 25), 1), 100);
            $items   = $query->paginate($perPage);

            return response()->json([
                'rows'       => view('subcategories._rows', ['subcategories' => $items])->render(),
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

        $categories = Category::where('status', 1)->orderBy('name')->get();
        return view('subcategories.index', compact('categories'));
    }

    public function show(Subcategory $subcategory)
    {
        return response()->json($subcategory->only('id', 'category_id', 'name', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'status'      => ['required', 'in:0,1'],
        ]);

        Subcategory::create($request->only('category_id', 'name', 'status'));

        return back()->with('success', 'Subcategory created successfully.');
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'status'      => ['required', 'in:0,1'],
        ]);

        $subcategory->update($request->only('category_id', 'name', 'status'));

        return back()->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return back()->with('success', 'Subcategory deleted successfully.');
    }
}
