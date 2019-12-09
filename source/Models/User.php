<?php

namespace Source\Models;

use Source\DataLayer\DataLayer;

/**
 * MODEL
 */
class User extends DataLayer
{
    private const ENTITY = 'users';

    /**
     * Herda atributos e ações do DataLayer.
     * Inherits attributes and actions from DataLayer
     **/
    public function __construct()
    {
        parent::__construct(self::ENTITY, ['name', 'pass', 'email', 'cpf', 'genre',
            'birthdate', 'phone'], 'id', true);
    }
}
