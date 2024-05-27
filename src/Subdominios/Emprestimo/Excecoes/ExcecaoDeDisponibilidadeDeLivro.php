<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes;

class ExcecaoDeDisponibilidadeDeLivro extends \Exception
{
    public function __construct(string $mensagem)
    {
        parent::__construct($mensagem);
    }
}