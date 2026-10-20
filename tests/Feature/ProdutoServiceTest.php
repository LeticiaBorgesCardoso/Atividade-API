<?php

namespace Tests\Feature;

use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProdutoServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProdutoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProdutoService();
    }

    /** Criação: não cria se o preço não respeita a margem mínima */
    public function test_nao_cria_produto_com_preco_abaixo_da_margem_minima(): void
    {
        try {
            // custo 100 => mínimo 110.00; preço 105 é inválido
            $this->service->create([
                'nome'        => 'Produto Inválido',
                'preco'       => 105,
                'preco_custo' => 100,
            ]);

            $this->fail('Deveria ter lançado ValidationException.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('preco', $e->errors());
        }

        $this->assertDatabaseMissing('produtos', ['nome' => 'Produto Inválido']);
        $this->assertDatabaseCount('produtos', 0);
    }

    /** Update 1: atualiza com sucesso quando a margem é respeitada */
    public function test_atualiza_produto_com_dados_validos(): void
    {
        $produto = Produto::create([
            'nome'        => 'Nome Antigo',
            'preco'       => 150,
            'preco_custo' => 100,
        ]);

        $atualizado = $this->service->update($produto, [
            'nome'  => 'Nome Novo',
            'preco' => 120, // mínimo é 110, então é válido
        ]);

        $this->assertEquals('Nome Novo', $atualizado->nome);
        $this->assertEquals(120, $atualizado->preco);
        $this->assertDatabaseHas('produtos', [
            'id'   => $produto->id,
            'nome' => 'Nome Novo',
        ]);
    }

    /** Update 2: não atualiza se o novo preço viola a margem e mantém os dados originais */
    public function test_nao_atualiza_produto_com_preco_abaixo_da_margem_minima(): void
    {
        $produto = Produto::create([
            'nome'        => 'Produto Original',
            'preco'       => 150,
            'preco_custo' => 100,
        ]);

        try {
            $this->service->update($produto, ['preco' => 105]); // mínimo é 110

            $this->fail('Deveria ter lançado ValidationException.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('preco', $e->errors());
        }

        $this->assertDatabaseHas('produtos', [
            'id'    => $produto->id,
            'preco' => 150,
        ]);
    }
}