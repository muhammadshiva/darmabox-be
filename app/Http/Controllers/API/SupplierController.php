<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return Supplier::orderByDesc('id')->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'email' => ['nullable', 'email', 'max:190', 'unique:suppliers,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
        $data['created_at'] = now();
        $supplier = Supplier::create($data);
        return response()->json($supplier, 201);
    }

    public function show(Supplier $supplier)
    {
        return $supplier;
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:200'],
            'email' => ['nullable', 'email', 'max:190', 'unique:suppliers,email,' . $supplier->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
        $supplier->update($data);
        return $supplier;
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->noContent();
    }
}
