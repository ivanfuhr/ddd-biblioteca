<?php

namespace IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Fixtures;

use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\Livro;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Enums\DisponibilidadeLivro;

class LivroFixture
{
    /**
     * @return Livro[]
     */
    public static function getAllLivros(): array
    {
        return [
            new Livro(
                id: 1,
                titulo: 'Código Limpo: Habilidades Práticas do Agile Software',
                disponibilidade: DisponibilidadeLivro::DISPONIVEL
            ),
            new Livro(
                id: 2,
                titulo: 'O Programador Pragmático: Aprendizagem de um mestre',
                disponibilidade: DisponibilidadeLivro::DISPONIVEL
            ),
            new Livro(
                id: 3,
                titulo: 'Padrões de Projeto: Soluções Reutilizáveis de Software Orientado a Objetos',
                disponibilidade: DisponibilidadeLivro::EMPRESTADO
            )
        ];
    }

    /**
     * @param int $id
     * @return Livro|null
     */
    public static function getLivroById(int $id): ?Livro
    {
        foreach (self::getAllLivros() as $livro) {
            if ($livro->getId() === $id) {
                return $livro;
            }
        }

        return null;
    }
}
