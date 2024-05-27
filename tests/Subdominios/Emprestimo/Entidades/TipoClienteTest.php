<?php

namespace IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Entidades;

use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\TipoCliente;
use PHPUnit\Framework\TestCase;

class TipoClienteTest extends TestCase
{
    public function testCriarTipoCliente()
    {
        $tipoCliente = new TipoCliente(
            id: 1,
            descricao: 'Aluno',
            prazoDevolucao: 7,
            quantidadeMaximaLivros: 3,
            quantidadeMaximaRenovacoes: 1,
            multaDiaria: 0.5
        );

        $this->assertEquals(1, $tipoCliente->getId());
        $this->assertEquals('Aluno', $tipoCliente->getDescricao());
        $this->assertEquals(7, $tipoCliente->getPrazoDevolucao());
        $this->assertEquals(3, $tipoCliente->getQuantidadeMaximaLivros());
        $this->assertEquals(1, $tipoCliente->getQuantidadeMaximaRenovacoes());
        $this->assertEquals(0.5, $tipoCliente->getMultaDiaria());
    }
}
