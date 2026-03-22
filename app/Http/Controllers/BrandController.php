<?php

namespace App\Http\Controllers;

use App\DataTables\BrandsDataTable;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BrandsDataTable $dataTable)
    {
        $status = request('status', 'all');
        return $dataTable->render('admin.brand.index', compact('status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:60|unique:brands,name',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Brand::create($validated);

        return redirect()->route('admin.brand.index')->with('success', 'Brand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
                Rule::unique('brands', 'name')->ignore($brand->brand_id, 'brand_id'),
            ],
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $brand->update($validated);

        return redirect()->route('admin.brand.index')->with('success', 'Brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()->route('admin.brand.index')->with('success', 'Brand soft deleted successfully.');
    }

    public function restore(int $brandId)
    {
        $brand = Brand::withTrashed()->findOrFail($brandId);

        if ($brand->trashed()) {
            $brand->restore();
        }

        return redirect()->route('admin.brand.index', ['status' => 'trashed'])->with('success', 'Brand recovered successfully.');
    }

    public function forceDestroy(int $brandId)
    {
        $brand = Brand::withTrashed()->findOrFail($brandId);
        $brand->forceDelete();

        return redirect()->route('admin.brand.index')->with('success', 'Brand deleted permanently.');
    }
}
