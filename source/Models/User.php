<?php

namespace Source\Models;

use Source\DataLayer\DataLayer;

/**
 * MODEL
 */
class User extends DataLayer
{
    const ENTITY = 'users';

    public function __construct()
    {
        parent::__construct(self::ENTITY, ['name', 'pass', 'email', 'cpf', 'genre',
            'birthdate', 'phone'], 'id', true);
    }
}
