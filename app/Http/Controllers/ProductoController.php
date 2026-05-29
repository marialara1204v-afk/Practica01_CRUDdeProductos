<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Producto::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
        ]);

        $producto = new Producto();
        $producto->nombre = $data['nombre'];
        $producto->descripcion = $data['descripcion'] ?? null;
        $producto->precio = $data['precio'];
        $producto->stock = $data['stock'] ?? 0;
        $producto->save();

        return response()->json($producto, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return response()->json($producto, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
        ]);

        if (array_key_exists('nombre', $data)) {
            $producto->nombre = $data['nombre'];
        }

        if (array_key_exists('descripcion', $data)) {
            $producto->descripcion = $data['descripcion'];
        }

        if (array_key_exists('precio', $data)) {
            $producto->precio = $data['precio'];
        }

        if (array_key_exists('stock', $data)) {
            $producto->stock = $data['stock'];
        }

        $producto->save();

        return response()->json($producto, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return response()->json(null, 204);
    }
}
