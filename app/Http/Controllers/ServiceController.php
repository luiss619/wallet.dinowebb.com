<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Service::with('category', 'subcategory');

            if ($s = $request->input('search')) {
                $query->where('services.name', 'like', "%{$s}%")
                      ->orWhereHas('category',    fn ($q) => $q->where('name', 'like', "%{$s}%"))
                      ->orWhereHas('subcategory', fn ($q) => $q->where('name', 'like', "%{$s}%"));
            }

            $sort = $request->input('sort');
            $dir  = $request->input('dir') === 'desc' ? 'desc' : 'asc';

            if ($sort === 'category') {
                $query->leftJoin('categories', 'services.category_id', '=', 'categories.id')
                      ->select('services.*')
                      ->orderBy('categories.name', $dir);
            } elseif ($sort === 'subcategory') {
                $query->leftJoin('subcategories', 'services.subcategory_id', '=', 'subcategories.id')
                      ->select('services.*')
                      ->orderBy('subcategories.name', $dir);
            } elseif (in_array($sort, ['name', 'status'])) {
                $query->orderBy($sort, $dir);
            } else {
                $query->orderBy('services.name');
            }

            $perPage = min(max((int) $request->input('per_page', 25), 1), 100);
            $items   = $query->paginate($perPage);

            return response()->json([
                'rows'       => view('services._rows', ['services' => $items])->render(),
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

        $categories    = Category::where('status', 1)->orderBy('name')->get();
        $subcategories = Subcategory::where('status', 1)->orderBy('name')->get();
        return view('services.index', compact('categories', 'subcategories'));
    }

    public function show(Service $service)
    {
        return response()->json($service->only('id', 'name', 'category_id', 'subcategory_id', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'category_id'    => ['nullable', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'status'         => ['required', 'in:0,1'],
        ]);

        Service::create($request->only('name', 'category_id', 'subcategory_id', 'status'));

        return back()->with('success', 'Service created successfully.');
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'category_id'    => ['nullable', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'status'         => ['required', 'in:0,1'],
        ]);

        $service->update($request->only('name', 'category_id', 'subcategory_id', 'status'));

        return back()->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Service deleted successfully.');
    }
}
