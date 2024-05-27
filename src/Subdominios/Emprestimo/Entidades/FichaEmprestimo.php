<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades;


use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\ObjetosDeValor\PeriodoEmprestimo;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDePagamentoDeMulta;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes\ExcecaoDeRenovacaoDeEmpreestimo;

/**
 * @property PeriodoEmprestimo[] $periodosEmprestimo
 */
class FichaEmprestimo
{
    /**
     * @throws ExcecaoDePagamentoDeMulta
     */
    public function __construct(
        private readonly int                   $id,
        private readonly Livro                 $livro,
        private readonly Cliente               $cliente,
        private array                          $periodosEmprestimo = [],
        private readonly ?ComprovamenteDeMulta $comprovanteDeMulta = null
    )
    {
        if (!empty($this->periodosEmprestimo)) $this->verificarMulta();
    }

    public function getComprovanteDeMulta(): ?ComprovamenteDeMulta
    {
        return $this->comprovanteDeMulta;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLivro(): Livro
    {
        return $this->livro;
    }

    public function getCliente(): Cliente
    {
        return $this->cliente;
    }

    /**
     * @return PeriodoEmprestimo[]
     */
    public function getPeriodosEmprestimo(): array
    {
        return $this->periodosEmprestimo;
    }

    /**
     * @throws ExcecaoDeRenovacaoDeEmpreestimo
     */
    public function adicionarPrimeiroPeriodoEmprestimo()
    {
        if (count($this->periodosEmprestimo) > 0)
            throw new ExcecaoDeRenovacaoDeEmpreestimo('Não é possível criar um novo período de empréstimo para um empréstimo que já possui períodos de empréstimo');

        $dataInicio = new \DateTimeImmutable();
        $dataFim = $dataInicio->modify(sprintf('+%d days', $this->cliente->getTipoCliente()->getPrazoDevolucao()));

        $this->periodosEmprestimo[] = new PeriodoEmprestimo($dataInicio, $dataFim);
    }

    /**
     * @throws ExcecaoDeRenovacaoDeEmpreestimo
     */
    function renovarEmprestimo(): void
    {
        $quantidadePeriodosEmprestimo = count($this->periodosEmprestimo);

        if (
            $quantidadePeriodosEmprestimo === 0 ||
            $quantidadePeriodosEmprestimo > $this->cliente->getTipoCliente()->getQuantidadeMaximaRenovacoes()
        )
            throw new ExcecaoDeRenovacaoDeEmpreestimo('Não é possível renovar o empréstimo');

        if (
            !empty($this->livro->getFilaEspera())
        )
            throw new ExcecaoDeRenovacaoDeEmpreestimo('Não é possível renovar o empréstimo, existem clientes na fila de espera');

        $ultimoPeriodoEmprestimo = end($this->periodosEmprestimo);

        $novoPeriodoEmprestimo = new PeriodoEmprestimo(
            $ultimoPeriodoEmprestimo->getDataFim(),
            $ultimoPeriodoEmprestimo->getDataFim()->modify(
                sprintf('+%d days', $this->cliente->getTipoCliente()->getPrazoDevolucao())
            )
        );

        $this->periodosEmprestimo[] = $novoPeriodoEmprestimo;
    }

    private function verificarMulta(): void
    {
        $periodoEmprestimo = end($this->periodosEmprestimo);
        $toleranceInterval = new \DateInterval('P1D');

        if (empty($this->comprovanteDeMulta) && $periodoEmprestimo->getDataFim()->add($toleranceInterval) < new \DateTimeImmutable()) {
            throw new ExcecaoDePagamentoDeMulta('O empréstimo está com multa pendente de pagamento');
        }
    }
}