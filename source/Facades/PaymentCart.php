<?php

namespace Source\Facades;

use Source\Models\Order;

/**
 * Classe responsável pela a aplicação de pagamento do carrinho de compras.
 * Class responsible for shopping cart payment application.
 */
class PaymentCart
{
    /**
     * Inicializa a sessão de pagamento.
     * Initializes the payment session.
     **/
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION['payment'] = (!empty($_SESSION['payment']) ? $_SESSION['payment'] : []);
    }

    /**
     * Retorna a sessão de pagamento do sistema.
     * Returns the system payment session.
     * @return type
     **/
    public function payment()
    {
        return $_SESSION['payment'];
    }

    /**
     * Adiciona os dados de pagamento na sessão do sistema.
     * Adds the payment data to the system session.
     * @return void
     **/
    public function add(Order $order): void
    {
        $_SESSION['payment'] = $order->data();
    }

    /**
     * Esvazia sessão de pagamento do sistema.
     * Empties system payment session.
     * @return void
     **/
    public function clear(): void
    {
        $_SESSION['payment'] = [];
    }
}
