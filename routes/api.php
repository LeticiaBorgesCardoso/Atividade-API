<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiExternaController;
use App\Http\Controllers\ProdutoController;

// Rota do consumo da API externa
Route::get('/externa/bancos', [ApiExternaController::class, 'consumirApiExterna']);

// Rotas RESTful do cadastro de produtos
Route::apiResource('produtos', ProdutoController::class);