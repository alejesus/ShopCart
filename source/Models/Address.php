<?php

namespace Source\Models;

use Source\DataLayer\DataLayer;

/**
 * Classe responsável pela modelagem dos endereços do usuário do sistema.
 * Class responsible for modeling system user addresses.
 */
class Address extends DataLayer
{
    private const ENTITY = 'address';

    /**
     * Herda atributos e ações do DataLayer.
     * Inherits attributes and actions from DataLayer
     **/
    public function __construct()
    {
        parent::__construct(self::ENTITY, ['user_id', 'cep', 'logradouro',
            'number', 'complement', 'bairro', 'city', 'uf'], 'id', true);
    }
}
