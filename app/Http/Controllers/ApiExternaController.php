<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiExternaController extends Controller
{
    public function consumirApiExterna()
    {
        // Consumindo a API pública do BrasilAPI (Lista de Bancos)
        $response = Http::get('https://brasilapi.com.br/api/banks/v1');

        if ($response->successful()) {
            return response()->json([
                'status' => 'Sucesso',
                'dados' => $response->json()
            ], 200);
        }

        return response()->json(['erro' => 'Falha ao consumir API externa'], 500);
    }
}