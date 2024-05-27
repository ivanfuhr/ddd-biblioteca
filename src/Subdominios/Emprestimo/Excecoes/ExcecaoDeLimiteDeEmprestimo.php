<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes;

class ExcecaoDeLimiteDeEmprestimo extends \Exception
{
    public function __construct(string $message = 'Limite de empréstimo atingido')
    {
        parent::__construct($message);
    }
}