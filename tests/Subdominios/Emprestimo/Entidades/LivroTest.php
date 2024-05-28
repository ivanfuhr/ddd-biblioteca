<?php

namespace IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Entidades;

use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\Livro;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Enums\DisponibilidadeLivro;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDeDisponibilidadeDeLivro;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDeFilaDeEspera;
use IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Fixtures\ClienteFixture;
use IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Fixtures\LivroFixture;
use PHPUnit\Framework\TestCase;

class LivroTest extends TestCase
{
    public function testCriarLivro()
    {
        $livro = new Livro(
            id: 4,
            titulo: 'Domain Driven Design: Atacando as Complexidades no Coração do Software',
            disponibilidade: DisponibilidadeLivro::DISPONIVEL
        );

        $this->assertEquals(4, $livro->getId());
        $this->assertEquals('Domain Driven Design: Atacando as Complexidades no Coração do Software', $livro->getTitulo());
        $this->assertEquals(DisponibilidadeLivro::DISPONIVEL, $livro->getDisponibilidade());
    }

    public function testEmprestarLivroDisponivel() {
        $livro = LivroFixture::getLivroById(1);

        $livro->emprestar();

        $this->assertEquals(DisponibilidadeLivro::EMPRESTADO, $livro->getDisponibilidade());
    }

    public function testEmprestarLivroEmprestado()
    {
        $livro = LivroFixture::getLivroById(3);

        $this->expectExceptionObject(
            new ExcecaoDeDisponibilidadeDeLivro('Livro já emprestado')
        );

        $livro->emprestar();
    }

    public function testDevolverLivroEmprestado()
    {
        $livro = LivroFixture::getLivroById(3);

        $livro->devolver();

        $this->assertEquals(DisponibilidadeLivro::DISPONIVEL, $livro->getDisponibilidade());
    }

    public function testDevolverLivroDisponivel()
    {
        $livro = LivroFixture::getLivroById(1);

        $this->expectExceptionObject(
            new ExcecaoDeDisponibilidadeDeLivro('Livro já disponível')
        );

        $livro->devolver();
    }

    public function testAdicionarClienteFilaEspera()
    {
        $livro = LivroFixture::getLivroById(3);
        $cliente = ClienteFixture::getClienteById(1);

        $livro->adicionarClienteFilaEspera($cliente);

        $this->assertEquals([$cliente], $livro->getFilaEspera());
    }

    public function testAdicionarClienteFilaEsperaLivroDisponivel()
    {
        $livro = LivroFixture::getLivroById(1);
        $cliente = ClienteFixture::getClienteById(1);

        $this->expectExceptionObject(
            new ExcecaoDeDisponibilidadeDeLivro('Livro disponível, não é possível adicionar cliente na fila de espera')
        );

        $livro->adicionarClienteFilaEspera($cliente);
    }

    public function testAdicionarClienteFilaEsperaClienteJaNaFila()
    {
        $livro = LivroFixture::getLivroById(3);
        $cliente = ClienteFixture::getClienteById(1);

        $livro->adicionarClienteFilaEspera($cliente);

        $this->expectExceptionObject(
            new ExcecaoDeFilaDeEspera('Cliente já está na fila de espera')
        );

        $livro->adicionarClienteFilaEspera($cliente);
    }

    public function testEmprestarLivroComFilaEspera()
    {
        $livro = LivroFixture::getLivroById(3);
        $cliente = ClienteFixture::getClienteById(1);

        $livro->adicionarClienteFilaEspera($cliente);
        $livro->devolver();

        $livro->emprestar($cliente);

        $this->assertEquals(DisponibilidadeLivro::EMPRESTADO, $livro->getDisponibilidade());
        $this->assertEquals([], $livro->getFilaEspera());
    }

    public function testEmprestarLivroComFilaEsperaClienteNaoInformado()
    {
        $livro = LivroFixture::getLivroById(3);
        $cliente = ClienteFixture::getClienteById(1);

        $livro->adicionarClienteFilaEspera($cliente);
        $livro->devolver();

        $this->expectExceptionObject(
            new ExcecaoDeFilaDeEspera('Cliente não informado')
        );

        $livro->emprestar();
    }

    public function testEmprestarLivroComFilaEsperaClienteNaoProximo()
    {
        $livro = LivroFixture::getLivroById(3);
        $cliente1 = ClienteFixture::getClienteById(1);

        $livro->adicionarClienteFilaEspera($cliente1);
        $livro->devolver();

        $cliente2 = ClienteFixture::getClienteById(2);

        $this->expectExceptionObject(
            new ExcecaoDeFilaDeEspera('Cliente não é o próximo da fila de espera')
        );

        $livro->emprestar($cliente2);
    }

}
