<?php

namespace IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Entidades;

use DateTimeImmutable;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\Cliente;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\ComprovamenteDeMulta;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\FichaEmprestimo;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\ObjetosDeValor\Endereco;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\ObjetosDeValor\PeriodoEmprestimo;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDeLimiteDeEmprestimo;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDePagamentoDeMulta;
use IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Fixtures\ClienteFixture;
use IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Fixtures\LivroFixture;
use IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Fixtures\TipoClienteFixture;
use PHPUnit\Framework\TestCase;

class ClienteTest extends TestCase
{
    public function testCriarCliente()
    {
        $endereco = new Endereco(
            logradouro: 'Rua das Flores',
            numero: '123',
            complemento: 'Apto 101',
            bairro: 'Centro',
            cidade: 'São Paulo',
            estado: 'SP',
            cep: '12345-678'
        );

        $cliente = new Cliente(
            id: 1,
            nome: 'Fulano de Tal',
            email: 'fulano.de.tal@gmail.com',
            telefone: '11 99999-9999',
            endereco: $endereco,
            tipoCliente: TipoClienteFixture::getTipoClienteById(1),
            emprestimosAtivos: []
        );

        $this->assertEquals(1, $cliente->getId());
        $this->assertEquals('Fulano de Tal', $cliente->getNome());
        $this->assertEquals($endereco, $cliente->getEndereco());
        $this->assertEquals(TipoClienteFixture::getTipoClienteById(1), $cliente->getTipoCliente());
        $this->assertEquals([], $cliente->getEmprestimosAtivos());
    }

    public function testAdicionarEmprestimoAtivo()
    {
        $cliente = ClienteFixture::getClienteById(1);
        $livro = LivroFixture::getLivroById(1);

        $fichaEmprestimo = new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente
        );

        $cliente->adicionarEmprestimo($fichaEmprestimo);
        $this->assertEquals([$fichaEmprestimo], $cliente->getEmprestimosAtivos());
    }

    public function testRemoverEmprestimoAtivo()
    {
        $cliente = ClienteFixture::getClienteById(1);
        $livro = LivroFixture::getLivroById(1);

        $fichaEmprestimo = new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente
        );

        $cliente->adicionarEmprestimo($fichaEmprestimo);
        $cliente->devolverEmprestimo($fichaEmprestimo);

        $this->assertEquals([], $cliente->getEmprestimosAtivos());
    }

    public function testRemoverEmprestimoAtivoInexistente()
    {
        $cliente = ClienteFixture::getClienteById(1);
        $livro = LivroFixture::getLivroById(1);

        $fichaEmprestimo = new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente
        );

        $this->expectExceptionObject(
            new \InvalidArgumentException('Empréstimo não encontrado')
        );

        $cliente->devolverEmprestimo($fichaEmprestimo);
    }

    public function testLimiteEmprestimosAtivos()
    {
        $cliente = ClienteFixture::getClienteById(3);
        $livro1 = LivroFixture::getLivroById(1);
        $livro2 = LivroFixture::getLivroById(2);

        $fichaEmprestimo1 = new FichaEmprestimo(
            id: 1,
            livro: $livro1,
            cliente: $cliente
        );

        $fichaEmprestimo2 = new FichaEmprestimo(
            id: 2,
            livro: $livro2,
            cliente: $cliente
        );

        $cliente->adicionarEmprestimo($fichaEmprestimo1);

        $this->expectExceptionObject(
            new ExcecaoDeLimiteDeEmprestimo('Limite de empréstimos ativos atingido')
        );

        $cliente->adicionarEmprestimo($fichaEmprestimo2);
    }

    public function testDevolverLivroComMultaNaoPaga()
    {
        $cliente = ClienteFixture::getClienteById(1);
        $livro = LivroFixture::getLivroById(1);

        $dataVencimento = (new DateTimeImmutable())->modify('-5 days');
        $fichaEmprestimo = new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente,
            periodosEmprestimo: [
                new PeriodoEmprestimo(
                    (new DateTimeImmutable())->modify('-10 days'),
                    $dataVencimento
                )
            ],
            comprovanteDeMulta: new ComprovamenteDeMulta(
                id: 1,
                cliente: $cliente,
                dataVencimento: $dataVencimento
            )
        );

        $cliente->adicionarEmprestimo($fichaEmprestimo);

        $this->expectExceptionObject(
            new ExcecaoDePagamentoDeMulta('Empréstimo com multa não pode ser devolvido')
        );

        $cliente->devolverEmprestimo($fichaEmprestimo);
    }

    public function testDevolverLivroComMultaPaga()
    {
        $cliente = ClienteFixture::getClienteById(1);
        $livro = LivroFixture::getLivroById(1);

        $dataVencimento = (new DateTimeImmutable())->modify('-5 days');

        $fichaEmprestimo = new FichaEmprestimo(
            id: 1,
            livro: $livro,
            cliente: $cliente,
            periodosEmprestimo: [
                new PeriodoEmprestimo(
                    (new DateTimeImmutable())->modify('-10 days'),
                    $dataVencimento
                )
            ],
            comprovanteDeMulta: new ComprovamenteDeMulta(
                id: 1,
                cliente: $cliente,
                dataVencimento: $dataVencimento
            )
        );

        $cliente->adicionarEmprestimo($fichaEmprestimo);
        $fichaEmprestimo->getComprovanteDeMulta()->confirmarPagamento();

        $cliente->devolverEmprestimo($fichaEmprestimo);

        $this->assertEquals([], $cliente->getEmprestimosAtivos());
    }
}
