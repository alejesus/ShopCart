<?php

namespace Source\Models;

use Source\DataLayer\DataLayer;

/**
 * Classe responsável por modelar as informações para o BD
 * referente a tabela de pedidos.
 * Class responsible for modeling the order table information for the DB.
 */
class Order extends DataLayer
{
    const ENTITY = 'orders';

    /**
     * Inicializa as ações e atributos da classe DataLayer herdada.
     * Initializes the actions and attributes of the inherited DataLayer class.
     */
    public function __construct()
    {
        parent::__construct(self::ENTITY, ['transaction', 'reference', 'user_id',
            'fee_amount', 'net_amount', 'gross_amount', 'payment_method', 'total_amount',
            'status'], 'id', true);
    }
}
