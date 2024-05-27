<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Excecoes;

class ExcecaoDeRenovacaoDeEmpreestimo extends \Exception
{
    public function __construct(string $message = 'Não é possível renovar o livro')
    {
        parent::__construct($message);
    }
}