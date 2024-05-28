<?php

namespace IvanFuhr\BibliotecaTest\Subdominios\Emprestimo\Fixtures;

use IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\Cliente;
use IvanFuhr\Biblioteca\Subdominios\Emprestimo\ObjetosDeValor\Endereco;

class ClienteFixture
{
    /**
     * @return Cliente[]
     */
    public static function getAllClientes(): array
    {
        $tiposCliente = TipoClienteFixture::getAllTiposCliente();

        return [
            new Cliente(
                id: 1,
                nome: 'João da Silva',
                email: 'joao.da.silva@gmail.com',
                telefone: '11 99999-9999',
                endereco: new Endereco(
                    logradouro: 'Rua das Flores',
                    numero: '123',
                    complemento: 'Apto 101',
                    bairro: 'Centro',
                    cidade: 'São Paulo',
                    estado: 'SP',
                    cep: '12345-678'
                ),
                tipoCliente: $tiposCliente[0]
            ),
            new Cliente(
                id: 2,
                nome: 'Maria Oliveira',
                email: 'maria.oliveira@example.com',
                telefone: '21 88888-8888',
                endereco: new Endereco(
                    logradouro: 'Avenida Brasil',
                    numero: '500',
                    complemento: 'Casa',
                    bairro: 'Jardim América',
                    cidade: 'Rio de Janeiro',
                    estado: 'RJ',
                    cep: '54321-876'
                ),
                tipoCliente: $tiposCliente[1]
            ),
            new Cliente(
                id: 3,
                nome: 'Carlos Pereira',
                email: 'carlos.pereira@example.com',
                telefone: '31 77777-7777',
                endereco: new Endereco(
                    logradouro: 'Praça da Liberdade',
                    numero: '42',
                    complemento: 'Bloco B',
                    bairro: 'Savassi',
                    cidade: 'Belo Horizonte',
                    estado: 'MG',
                    cep: '67890-123'
                ),
                tipoCliente: $tiposCliente[2]
            )
        ];
    }

    /**
     * @param int $id
     * @return Cliente|null
     */
    public static function getClienteById(int $id): ?Cliente
    {
        foreach (self::getAllClientes() as $cliente) {
            if ($cliente->getId() === $id) {
                return $cliente;
            }
        }

        return null;
    }
}