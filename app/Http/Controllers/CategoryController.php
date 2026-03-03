<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = trim((string) request('search', ''));
        $status = (string) request('status', 'all');

        $query = Category::query();

        if ($status === 'trashed') {
            $query->onlyTrashed();
        } elseif ($status === 'active') {
            $query->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0);
        }

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->orderByDesc('category_id')->paginate(12)->withQueryString();

        return view('admin.category.index', compact('categories', 'search', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:60|unique:categories,name',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Category::create($validated);

        return redirect()->route('admin.category.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
                Rule::unique('categories', 'name')->ignore($category->category_id, 'category_id'),
            ],
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Category soft deleted successfully.');
    }

    public function restore(int $categoryId)
    {
        $category = Category::withTrashed()->findOrFail($categoryId);

        if ($category->trashed()) {
            $category->restore();
        }

        return redirect()->route('admin.category.index', ['status' => 'trashed'])->with('success', 'Category recovered successfully.');
    }

    public function forceDestroy(int $categoryId)
    {
        $category = Category::withTrashed()->findOrFail($categoryId);
        $category->forceDelete();

        return redirect()->route('admin.category.index')->with('success', 'Category deleted permanently.');
    }
}
