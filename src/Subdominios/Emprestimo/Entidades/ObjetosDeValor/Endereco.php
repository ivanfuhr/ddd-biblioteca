<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\ObjetosDeValor;

readonly class Endereco
{
    public function __construct(
        private string $logradouro,
        private string $numero,
        private string $complemento,
        private string $bairro,
        private string $cidade,
        private string $estado,
        private string $cep
    )
    {
    }

    public function getLogradouro(): string
    {
        return $this->logradouro;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function getComplemento(): string
    {
        return $this->complemento;
    }

    public function getBairro(): string
    {
        return $this->bairro;
    }

    public function getCidade(): string
    {
        return $this->cidade;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function getCep(): string
    {
        return $this->cep;
    }
}