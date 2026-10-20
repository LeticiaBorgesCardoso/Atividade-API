<?php 

namespace App\Services;

use App\Models\Produto;
use Illuminate\Validation\ValidationException;

class ProdutoService 
{
    private const MARGEM_MINIMA = 0.10;

    public function create(array $dados): Produto
    {
        $this->verificarMargem((float) $dados['preco'], (float) $dados['preco_custo']);

        return Produto::create($dados);
    }

    private function verificarMargem(float $preco, float $precoCusto): void
    {
        $minimo = round($precoCusto * (1 + self::MARGEM_MINIMA), 2);

        if ($preco < $minimo) {
            throw ValidationException::withMessages([
                'preco' => sprintf('O preço do produto deve ser no mínimo R$ %.2f (Margem Mínima)', $minimo),
            ]);
        }
    }
}