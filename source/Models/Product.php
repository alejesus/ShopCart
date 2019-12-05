<?php

namespace Source\Models;

use Source\DataLayer\DataLayer;

/**
 * Classe responsável por abstrair o objeto produto e modelar as
 * informações do BD referentes a tabela de produtos.
 * Class responsible for abstracting the product object and
 * modeling the DB table information.
 */
class Product extends DataLayer
{
    const ENTITY = 'products';

    /**
     * Inicializa camada DataLayer com a base de dados.
     * Initializes DataLayer layer with the database.
     **/
    public function __construct()
    {
        parent::__construct(self::ENTITY, ['title', 'name', 'price'], 'id', true);

    }
}
