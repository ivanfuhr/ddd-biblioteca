<?php

namespace IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Fixtures;

use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\TipoCliente;

class TipoClienteFixture
{
    /**
     * @return TipoCliente[]
     */
    public static function getAllTiposCliente(): array
    {
        return [
            new TipoCliente(
                id: 1,
                descricao: 'Estudante',
                prazoDevolucao: 7,
                quantidadeMaximaLivros: 3,
                quantidadeMaximaRenovacoes: 2,
                multaDiaria: 0.50
            ),
            new TipoCliente(
                id: 2,
                descricao: 'Professor',
                prazoDevolucao: 14,
                quantidadeMaximaLivros: 5,
                quantidadeMaximaRenovacoes: 3,
                multaDiaria: 0.25
            ),
            new TipoCliente(
                id: 3,
                descricao: 'Comunidade',
                prazoDevolucao: 7,
                quantidadeMaximaLivros: 1,
                quantidadeMaximaRenovacoes: 1,
                multaDiaria: 1.00
            )
        ];
    }

    /**
     * @param int $id
     * @return TipoCliente|null
     */
    public static function getTipoClienteById(int $id): ?TipoCliente
    {
        foreach (self::getAllTiposCliente() as $tipoCliente) {
            if ($tipoCliente->getId() === $id) {
                return $tipoCliente;
            }
        }

        return null;
    }
}