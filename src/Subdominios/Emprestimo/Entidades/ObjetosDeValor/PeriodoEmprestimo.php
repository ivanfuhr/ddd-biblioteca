<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\ObjetosDeValor;

use DateTimeImmutable;

readonly class PeriodoEmprestimo
{
    public function __construct(
        private DateTimeImmutable $dataInicio,
        private DateTimeImmutable $dataFim
    )
    {
    }

    public function getDataInicio(): DateTimeImmutable
    {
        return $this->dataInicio;
    }

    public function getDataFim(): DateTimeImmutable
    {
        return $this->dataFim;
    }
}