<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades;

use DateTimeImmutable;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Enums\StatusPagamentoMulta;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDePagamentoDeMulta;


class ComprovamenteDeMulta
{
    /**
     * @throws ExcecaoDePagamentoDeMulta
     */
    public function __construct(
        private readonly int               $id,
        private readonly Cliente           $cliente,
        private readonly DateTimeImmutable $dataVencimento,
        private ?float                     $valorTotal = null,
        private ?DateTimeImmutable         $dataPagamento = null,
        private ?StatusPagamentoMulta      $statusPagamento = StatusPagamentoMulta::AGUARDANDO_PAGAMENTO,
    )
    {
        if ($statusPagamento === StatusPagamentoMulta::AGUARDANDO_PAGAMENTO) {
            $this->valorTotal = $this->calcularValorTotal();
            $this->dataPagamento = null;
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCliente(): Cliente
    {
        return $this->cliente;
    }

    public function getValorTotal(): float
    {
        return $this->valorTotal;
    }

    public function getDataVencimento(): DateTimeImmutable
    {
        return $this->dataVencimento;
    }

    public function getStatusPagamento(): ?StatusPagamentoMulta
    {
        return $this->statusPagamento;
    }

    public function getDataPagamento(): ?DateTimeImmutable
    {
        return $this->dataPagamento;
    }

    /**
     * @throws ExcecaoDePagamentoDeMulta
     */
    public function confirmarPagamento()
    {
        if ($this->statusPagamento === StatusPagamentoMulta::PAGO) throw new ExcecaoDePagamentoDeMulta('Pagamento já confirmado');

        $this->statusPagamento = StatusPagamentoMulta::PAGO;

        $this->dataPagamento = new DateTimeImmutable();
    }

    /**
     * @throws ExcecaoDePagamentoDeMulta
     */
    private function calcularValorTotal(): float|int
    {
        $diasDeAtraso = $this->dataVencimento->diff(new DateTimeImmutable())->days;

        if ($diasDeAtraso <= 0)
            throw new ExcecaoDePagamentoDeMulta('Não é possível pagar uma multa antes do vencimento');

        return $this->cliente->getTipoCliente()->getMultaDiaria() * $diasDeAtraso;
    }

}