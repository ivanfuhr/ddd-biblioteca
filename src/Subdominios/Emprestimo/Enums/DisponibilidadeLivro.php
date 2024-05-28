<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Enums;

enum DisponibilidadeLivro: string
{
    case DISPONIVEL = 'Disponível';
    case EMPRESTADO = 'Emprestado';
}