<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes;

class ExcecaoDeFilaDeEspera extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}