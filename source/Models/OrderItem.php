<?php

namespace Source\Models;

use Source\DataLayer\DataLayer;

/**
 * Classe responsável por modelar as informações para o BD
 * referente a tabela de itens do pedido.
 * Class responsible for modeling the information for the DB
 * regarding the order item table.
 */
class OrderItem extends DataLayer
{
    const ENTITY = 'orders_item';

    /**
     * Inicializa as ações e atributos da classe DataLayer herdada.
     * Initializes the actions and attributes of the inherited DataLayer class.
     */
    public function __construct()
    {
        parent::__construct(self::ENTITY, ['order_id', 'product_id', 'price', 'quantity'
            , 'subtotal', 'total'], 'id', false);
    }
}
