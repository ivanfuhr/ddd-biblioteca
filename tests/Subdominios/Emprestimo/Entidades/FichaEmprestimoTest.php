<?php

namespace IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Entidades;

use DateInterval;
use DateTimeImmutable;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\FichaEmprestimo;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\ObjetosDeValor\PeriodoEmprestimo;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDePagamentoDeMulta;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDeRenovacaoDeEmpreestimo;
use IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Fixtures\ClienteFixture;
use IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Fixtures\LivroFixture;
use PHPUnit\Framework\TestCase;

class FichaEmprestimoTest extends TestCase
{
    public function testCriarFichaEmprestimo()
    {
        $livro = LivroFixture::getLivroById(1);
        $cliente = ClienteFixture::getClienteById(1);

        $fichaEmprestimo = new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente
        );

        $this->assertEquals(1, $fichaEmprestimo->getId());
        $this->assertEquals($livro, $fichaEmprestimo->getLivro());
        $this->assertEquals($cliente, $fichaEmprestimo->getCliente());
    }

    public function testAdicionarPeriodoEmprestimo()
    {
        $livro = LivroFixture::getLivroById(1);
        $cliente = ClienteFixture::getClienteById(1);

        $fichaEmprestimo = new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente
        );

        $fichaEmprestimo->adicionarPrimeiroPeriodoEmprestimo();

        $this->assertNotEmpty($fichaEmprestimo->getPeriodosEmprestimo());
        $expectedInterval = new DateInterval("P{$cliente->getTipoCliente()->getPrazoDevolucao()}D");
        $this->assertEquals(
            (new DateTimeImmutable())->add($expectedInterval)->format('Y-m-d'),
            $fichaEmprestimo->getPeriodosEmprestimo()[0]->getDataFim()->format('Y-m-d')
        );
    }

    public function testAdicionarPeriodoEmprestimoComPeriodo()
    {
        $livro = LivroFixture::getLivroById(1);
        $cliente = ClienteFixture::getClienteById(1);

        $fichaEmprestimo = new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente
        );

        $fichaEmprestimo->adicionarPrimeiroPeriodoEmprestimo();

        $this->expectExceptionObject(
            new ExcecaoDeRenovacaoDeEmpreestimo('Não é possível criar um novo período de empréstimo para um empréstimo que já possui períodos de empréstimo')
        );

        $fichaEmprestimo->adicionarPrimeiroPeriodoEmprestimo();
    }

    public function testRenovarPeriodoEmprestimo()
    {
        $livro = LivroFixture::getLivroById(1);
        $cliente = ClienteFixture::getClienteById(1);

        $fichaEmprestimo = new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente
        );

        $fichaEmprestimo->adicionarPrimeiroPeriodoEmprestimo();

        $fichaEmprestimo->renovarEmprestimo();

        $this->assertCount(2, $fichaEmprestimo->getPeriodosEmprestimo());
        $expectedInterval = new DateInterval("P{$cliente->getTipoCliente()->getPrazoDevolucao()}D");
        $this->assertEquals(
            (new DateTimeImmutable())->add($expectedInterval)->add($expectedInterval)->format('Y-m-d'),
            $fichaEmprestimo->getPeriodosEmprestimo()[1]->getDataFim()->format('Y-m-d')
        );
    }

    public function testRenovarPeriodoEmprestimoSemPeriodo()
    {
        $livro = LivroFixture::getLivroById(1);
        $cliente = ClienteFixture::getClienteById(1);

        $fichaEmprestimo = new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente
        );

        $this->expectExceptionObject(
            new ExcecaoDeRenovacaoDeEmpreestimo('Não é possível renovar o empréstimo')
        );

        $fichaEmprestimo->renovarEmprestimo();
    }

    public function testLimiteRenovarPeridioEmprestimo()
    {
        $livro = LivroFixture::getLivroById(1);
        $cliente = ClienteFixture::getClienteById(1);

        $fichaEmprestimo = new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente
        );

        $fichaEmprestimo->adicionarPrimeiroPeriodoEmprestimo();

        $fichaEmprestimo->renovarEmprestimo();
        $fichaEmprestimo->renovarEmprestimo();

        $this->expectExceptionObject(
            new ExcecaoDeRenovacaoDeEmpreestimo('Não é possível renovar o empréstimo')
        );

        $fichaEmprestimo->renovarEmprestimo();
    }

    public function testCarregarFichaEmprestimoVencidade()
    {
        $livro = LivroFixture::getLivroById(1);
        $cliente = ClienteFixture::getClienteById(1);

        $this->expectExceptionObject(
            new ExcecaoDePagamentoDeMulta('O empréstimo está com multa pendente de pagamento')
        );

        new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente,
            periodosEmprestimo: [
                new PeriodoEmprestimo(
                    (new DateTimeImmutable())->modify('-10 days'),
                    (new DateTimeImmutable())->modify('-5 days')
                )
            ]
        );
    }
}
