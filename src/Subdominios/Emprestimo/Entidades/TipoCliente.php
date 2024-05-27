<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades;

class TipoCliente
{
    public function __construct(
        private readonly int $id,
        private readonly string $descricao,
        private int $prazoDevolucao,
        private int $quantidadeMaximaLivros,
        private int $quantidadeMaximaRenovacoes,
        private float $multaDiaria,
    )
    {
    }

    public function getQuantidadeMaximaRenovacoes(): int
    {
        return $this->quantidadeMaximaRenovacoes;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrazoDevolucao(): int
    {
        return $this->prazoDevolucao;
    }

    public function getQuantidadeMaximaLivros(): int
    {
        return $this->quantidadeMaximaLivros;
    }

    public function getMultaDiaria(): float
    {
        return $this->multaDiaria;
    }

    public function alterarPrazoDevolucao(int $novoPrazoDevolucao): void
    {
        $this->prazoDevolucao = $novoPrazoDevolucao;
    }

    public function alterarQuantidadeMaximaLivros(int $novaQuantidadeMaximaLivros): void
    {
        $this->quantidadeMaximaLivros = $novaQuantidadeMaximaLivros;
    }

    public function alterarMultaDiaria(float $novaMultaDiaria): void
    {
        $this->multaDiaria = $novaMultaDiaria;
    }

    public function alterarQuantidadeMaximaRenovacoes(int $novaQuantidadeMaximaRenovacoes): void
    {
        $this->quantidadeMaximaRenovacoes = $novaQuantidadeMaximaRenovacoes;
    }
}