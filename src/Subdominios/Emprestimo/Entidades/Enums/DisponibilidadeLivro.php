<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\Enums;

enum DisponibilidadeLivro: string
{
    case DISPONIVEL = 'Disponível';
    case EMPRESTADO = 'Emprestado';
}