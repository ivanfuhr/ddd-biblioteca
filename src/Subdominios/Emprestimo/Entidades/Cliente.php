<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades;

use InvalidArgumentException;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Enums\StatusPagamentoMulta;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDeLimiteDeEmprestimo;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDePagamentoDeMulta;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\ObjetosDeValor\Endereco;

/**
 * @property FichaEmprestimo[] $emprestimosAtivos
 */
class Cliente
{
    public function __construct(
        private readonly int    $id,
        private readonly string $nome,
        private string          $email,
        private string          $telefone,
        private Endereco        $endereco,
        private TipoCliente     $tipoCliente,
        private array           $emprestimosAtivos = []
    )
    {
    }

    public function getTipoCliente(): TipoCliente
    {
        return $this->tipoCliente;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTelefone(): string
    {
        return $this->telefone;
    }

    public function getEndereco(): Endereco
    {
        return $this->endereco;
    }

    /**
     * @return FichaEmprestimo[]
     */
    public function getEmprestimosAtivos(): array
    {
        return $this->emprestimosAtivos;
    }

    public function novoEmail(string $email): void
    {
        $this->email = $email;
    }

    public function novoTelefone(string $telefone): void
    {
        $this->telefone = $telefone;
    }

    public function novoEndereco(Endereco $endereco): void
    {
        $this->endereco = $endereco;
    }

    public function alterarTipoCliente(TipoCliente $tipoCliente): void
    {
        $this->tipoCliente = $tipoCliente;
    }

    /**
     * @throws ExcecaoDeLimiteDeEmprestimo
     */
    public function adicionarEmprestimo(FichaEmprestimo $fichaEmprestimo): void
    {
        if (count($this->emprestimosAtivos) >= $this->tipoCliente->getQuantidadeMaximaLivros())
            throw new ExcecaoDeLimiteDeEmprestimo('Limite de empréstimos ativos atingido');

        $this->emprestimosAtivos[] = $fichaEmprestimo;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function devolverEmprestimo(FichaEmprestimo $fichaEmprestimo): void
    {
        $emprestimo = array_filter($this->emprestimosAtivos, fn($emprestimo) => $emprestimo->getId() === $fichaEmprestimo->getId());

        if (empty($emprestimo))
            throw new InvalidArgumentException('Empréstimo não encontrado');

        $emprestimo = array_values($emprestimo)[0];

        if (
            $emprestimo->getComprovanteDeMulta() !== null &&
            $emprestimo->getComprovanteDeMulta()->getStatusPagamento() !== StatusPagamentoMulta::PAGO
        )
            throw new ExcecaoDePagamentoDeMulta('Empréstimo com multa não pode ser devolvido');

        unset($this->emprestimosAtivos[array_search($emprestimo, $this->emprestimosAtivos)]);
    }
}