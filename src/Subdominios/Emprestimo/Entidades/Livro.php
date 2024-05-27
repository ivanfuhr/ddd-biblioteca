<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades;

use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\Enums\DisponibilidadeLivro;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDeDisponibilidadeDeLivro;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDeFilaDeEspera;

/**
 * @property Cliente[] $filaEspera
 */
class Livro
{
    public function __construct(
        private readonly int         $id,
        private readonly string      $titulo,
        private DisponibilidadeLivro $disponibilidade,
        private array                $filaEspera = []
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getDisponibilidade(): DisponibilidadeLivro
    {
        return $this->disponibilidade;
    }

    /**
     * @return Cliente[]
     */
    public function getFilaEspera(): array
    {
        return $this->filaEspera;
    }

    /**
     * @throws ExcecaoDeDisponibilidadeDeLivro
     * @throws ExcecaoDeFilaDeEspera
     */
    public function emprestar(Cliente $cliente = null): void
    {
        $this->verificarDisponibilidade();

        if(!empty($this->filaEspera)) $this->validarEmprestimoFilaDeEspera($cliente);

        $this->disponibilidade = DisponibilidadeLivro::EMPRESTADO;
    }

    /**
     * @throws ExcecaoDeDisponibilidadeDeLivro
     */
    public function devolver(): void
    {
        if ($this->disponibilidade === DisponibilidadeLivro::DISPONIVEL)
            throw new ExcecaoDeDisponibilidadeDeLivro('Livro já disponível');

        $this->disponibilidade = DisponibilidadeLivro::DISPONIVEL;
    }

    /**
     * @throws ExcecaoDeDisponibilidadeDeLivro
     * @throws ExcecaoDeFilaDeEspera
     */
    public function adicionarClienteFilaEspera(Cliente $cliente): void
    {
        $this->verificarIndisponibilidade();

        if (in_array($cliente, $this->filaEspera))
            throw new ExcecaoDeFilaDeEspera('Cliente já está na fila de espera');

        $this->filaEspera[] = $cliente;
    }

    /**
     * @throws ExcecaoDeFilaDeEspera
     */
    private function validarEmprestimoFilaDeEspera(?Cliente $cliente): void
    {
        if ($cliente === null)
            throw new ExcecaoDeFilaDeEspera('Cliente não informado');

        if ($cliente !== $this->filaEspera[0])
            throw new ExcecaoDeFilaDeEspera('Cliente não é o próximo da fila de espera');

        array_shift($this->filaEspera);
    }

    /**
     * @throws ExcecaoDeDisponibilidadeDeLivro
     */
    private function verificarDisponibilidade(): void
    {
        if ($this->disponibilidade === DisponibilidadeLivro::EMPRESTADO)
            throw new ExcecaoDeDisponibilidadeDeLivro('Livro já emprestado');
    }

    /**
     * @throws ExcecaoDeDisponibilidadeDeLivro
     */
    private function verificarIndisponibilidade(): void
    {
        if ($this->disponibilidade === DisponibilidadeLivro::DISPONIVEL)
            throw new ExcecaoDeDisponibilidadeDeLivro('Livro disponível, não é possível adicionar cliente na fila de espera');
    }
}