<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'nome'        => $this->nome,
            'descricao'   => $this->descricao,
            'preco'       => (float) $this->preco,
            'preco_custo' => (float) $this->preco_custo,
            'estoque'     => $this->estoque,
            'criado_em'   => $this->created_at,
        ];
    }
}