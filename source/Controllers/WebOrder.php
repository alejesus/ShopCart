<?php

namespace Source\Controllers;

use Source\Controllers\Address;
use Source\Controllers\Cart;
use Source\Controllers\Controller;
use Source\Controllers\WebConfirmation;
use Source\Controllers\WebIdentification;
use Source\Controllers\WebPayment;
use Source\Facades\ApplicationOrder;

/**
 * Classe responsável por controlar todo o fluxo de compras
 * (carrinho-> identificação -> endereço -> pagamento -> confirmação).
 * Class responsible for controlling the entire purchase flow
 * (cart-> identification -> address -> payment -> confirmation).
 */
class WebOrder extends Controller
{
    private $order;
    private $cart;
    private $identification;
    private $address;
    private $payment;
    private $confirmation;

    /**
     * Inicializa ações padrões de controlador e atributos de roteamento.
     * Initializes default controller actions and routing attributes.
     **/
    public function __construct($router)
    {
        parent::__construct($router);
        $this->order          = new ApplicationOrder();
        $this->cart           = new WebCart($router);
        $this->identification = new WebIdentification($router);
        $this->address        = new WebAddress($router);
        $this->payment        = new WebPayment($router);
        $this->confirmation   = new WebConfirmation($router);
    }

    /**
     * Exibe a sessão de pedidos
     * Displays ordering session
     * @return void
     */
    public function showOrder(): void
    {
        echo json_encode($this->order->showOrder());
    }

    /**
     * Exibe a página do carrinho de compras.
     * Display shopping cart page.
     * @return void
     */
    public function home(): void
    {
        $this->cart->home();
    }

    /**
     * Exibe a página de login do usuário.
     * Displays the user login page.
     * @return void
     */
    public function login(): void
    {
        $this->identification->login();
    }

    /**
     * Exibe a página de cadastro do usuário.
     * Displays the user register page.
     * @return void
     */
    public function register(): void
    {
        $this->identification->register();
    }

    /**
     * Exibe a página de recuperação de senha do usuário.
     * Displays the password recover user page.
     * @return void
     */
    public function recover(): void
    {
        $this->identification->recover();
    }

    /**
     * Exibe a página de endereço de entrega do pedido.
     * Displays the order shipping address page.
     * @return void
     */
    public function address(): void
    {
        $this->address->address();
    }

    /**
     * Exibe a página de pagamento do pedido.
     * Displays the order payment page.
     * @return void
     */
    public function payment(): void
    {
        $this->payment->payment();
    }

    /**
     * Exibe a página de confirmação de compra do pedido.
     * Displays the purchase order confirmation page.
     * @return void
     */
    public function confirmation(): void
    {
        $this->confirmation->confirmation();
    }
}
