<?php

namespace Source\Facades;

use Source\Models\Address;

/**
 * Classe responsável pela regra de negócio do endereço de entrega.
 * Class responsible for shipping address business rule.
 */
class DeliveryAddress
{
    /**
     * Inicializa sessões de endereço de entrega e frete.
     * Initializes shipping and shipping address sessions.
     **/
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION['address']  = (!empty($_SESSION['address']) ? $_SESSION['address'] : []);
        $_SESSION['shipping'] = (!empty($_SESSION['shipping']) ? $_SESSION['shipping'] : []);
    }

    /**
     * Retorna a sessão de entrega com o endereço selecionado pelo usuário.
     * Returns delivery session with user-selected address.
     * @return type
     **/
    public function address()
    {
        return $_SESSION['address'];
    }

    /**
     * Retorna a sessão do frete selecionado pelo usuário.
     * Returns the freight session selected by the user.
     * @return type
     **/
    public function shipping()
    {
        return $_SESSION['shipping'];
    }

    /**
     * Adiciona o endereço selecionado para a sessão de endereço.
     * Adds the selected address to the address session.
     * @return void
     **/
    public function add(Address $address): void
    {
        $_SESSION['address'] = $address->data();
    }

    /**
     * Adiciona o frete selecionado para a sessão de frete.
     * Adds the selected address to the shipping session.
     * @return void
     **/
    public function addShipping(array $shipping): void
    {
        $_SESSION['shipping'] = $shipping;
    }

}
