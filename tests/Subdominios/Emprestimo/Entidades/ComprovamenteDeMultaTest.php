<?php

namespace IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Entidades;

use DateTimeImmutable;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\ComprovamenteDeMulta;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\Enums\StatusPagamentoMulta;
use IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Fixtures\ClienteFixture;
use PHPUnit\Framework\TestCase;

class ComprovamenteDeMultaTest extends TestCase
{
    public function testCriarComprovanteDeMulta()
    {
        $cliente = ClienteFixture::getClienteById(1);

        $comprovanteDeMulta = new ComprovamenteDeMulta(
            id: 1,
            cliente: $cliente,
            dataVencimento: (new DateTimeImmutable())->modify('-3 day'),
        );

        $this->assertEquals(1, $comprovanteDeMulta->getId());
        $this->assertEquals(ClienteFixture::getClienteById(1), $comprovanteDeMulta->getCliente());
        $this->assertEquals(3 * $cliente->getTipoCliente()->getMultaDiaria(), $comprovanteDeMulta->getValorTotal());
        $this->assertEquals(StatusPagamentoMulta::AGUARDANDO_PAGAMENTO, $comprovanteDeMulta->getStatusPagamento());
        $this->assertNull($comprovanteDeMulta->getDataPagamento());
    }

    public function testCriarComprovanteDeMultaComPagamento()
    {
        $cliente = ClienteFixture::getClienteById(1);

        $comprovanteDeMulta = new ComprovamenteDeMulta(
            id: 1,
            cliente: $cliente,
            dataVencimento: (new DateTimeImmutable())->modify('-3 day'),
        );

        $comprovanteDeMulta->confirmarPagamento();

        $this->assertEquals(StatusPagamentoMulta::PAGO, $comprovanteDeMulta->getStatusPagamento());
        $this->assertNotNull($comprovanteDeMulta->getDataPagamento());
    }
}
