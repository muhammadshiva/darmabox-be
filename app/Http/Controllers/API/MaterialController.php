<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        return Material::orderByDesc('id')->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200', 'unique:materials,name'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
        ]);
        $material = Material::create($data);
        return response()->json($material, 201);
    }

    public function show(Material $material)
    {
        return $material;
    }

    public function update(Request $request, Material $material)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:200', 'unique:materials,name,' . $material->id],
            'unit' => ['sometimes', 'string', 'max:50'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
        ]);
        $material->update($data);
        return $material;
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return response()->noContent();
    }
}
