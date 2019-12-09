<?php

namespace Source\Models;

use Source\DataLayer\DataLayer;

/**
 * Classe responsável por representar a entidade Carrinho da base de dados.
 * Class responsible for representing the Database Cart entity.
 */
class Cart extends DataLayer
{
    private const ENTITY = 'carts';

    /**
     * Herda atributos e ações do DataLayer.
     * Inherits attributes and actions from DataLayer
     **/
    public function __construct()
    {
        parent::__construct(self::ENTITY, ['user_id', 'subtotal', 'total'], 'id', true);
    }
}
