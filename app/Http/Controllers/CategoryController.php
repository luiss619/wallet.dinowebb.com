<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($s = $request->input('search')) {
            $query->where('name', 'like', "%{$s}%");
        }

        $sortable = ['name', 'status'];
        $sort = in_array($request->input('sort'), $sortable) ? $request->input('sort') : 'name';
        $dir  = $request->input('dir') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sort, $dir);

        $perPage = min(max((int) $request->input('per_page', 25), 1), 100);
        $items   = $query->paginate($perPage);

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('categories._rows', ['categories' => $items])->render(),
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

        return view('categories.index');
    }

    public function show(Category $category)
    {
        return response()->json($category->only('id', 'name', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:0,1'],
        ]);

        Category::create($request->only('name', 'status'));

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:0,1'],
        ]);

        $category->update($request->only('name', 'status'));

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
}
