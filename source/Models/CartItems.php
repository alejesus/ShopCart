<?php

namespace Source\Models;

use Source\DataLayer\DataLayer;

/**
 * Classe responsável por representar a entidade de Itens do Carrinho da base de dados.
 * Class responsible for representing the Cart Items entity in the database.
 */
class CartItems extends DataLayer
{
    private const ENTITY = 'carts_item';

    /**
     * Herda atributos e ações do DataLayer.
     * Inherits attributes and actions from DataLayer
     **/
    public function __construct()
    {
        parent::__construct(self::ENTITY, ['cart_id', 'product_id', 'price', 'quantity']
            , 'id', false);
    }
}
