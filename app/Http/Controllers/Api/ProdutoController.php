<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Http\Resources\ProdutoResource;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        return ProdutoResource::collection(Produto::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'        => 'required|string|max:255',
            'descricao'   => 'nullable|string',
            'preco'       => 'required|numeric',
            'preco_custo' => 'required|numeric',
            'estoque'     => 'required|integer',
        ]);

        $produto = Produto::create($validated);

        return new ProdutoResource($produto);
    }

    public function show(string $id)
    {
        $produto = Produto::findOrFail($id);
        return new ProdutoResource($produto);
    }

    public function update(Request $request, string $id)
    {
        $produto = Produto::findOrFail($id);

        $validated = $request->validate([
            'nome'        => 'sometimes|required|string|max:255',
            'descricao'   => 'nullable|string',
            'preco'       => 'sometimes|required|numeric',
            'preco_custo' => 'sometimes|required|numeric',
            'estoque'     => 'sometimes|required|integer',
        ]);

        $produto->update($validated);

        return new ProdutoResource($produto);
    }

    public function destroy(string $id)
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();

        return response()->json(['message' => 'Produto removido com sucesso'], 200);
    }
}