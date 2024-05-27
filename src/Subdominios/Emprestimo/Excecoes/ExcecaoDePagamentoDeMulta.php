<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes;

class ExcecaoDePagamentoDeMulta extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}