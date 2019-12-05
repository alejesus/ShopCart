<?php

namespace Source\Models;

use Source\DataLayer\DataLayer;

/**
 * Classe responsável por representar a entidade Carrinho da base de dados.
 * Class responsible for representing the Database Cart entity.
 */
class Cart extends DataLayer
{
    const ENTITY = 'carts';

    public function __construct()
    {
        parent::__construct(self::ENTITY, ['user_id', 'subtotal', 'total'], 'id', true);
    }
}
