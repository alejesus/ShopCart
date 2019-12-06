<?php

namespace Source\Controllers;

use Source\Facades\ApplicationOrder;

/**
 * Classe responsável pela requisição, manipulação e tratamento dos dados da
 * página de confirmação de pedido.
 * Class responsible for requesting, handling and processing order confirmation page data.
 */
class WebConfirmation extends Controller
{
    private $order;

    /**
     * Inicializa informações da classe Controller e Facade de Pedidos.
     * Initializes Controller class and Order Facade information.
     */
    public function __construct($router)
    {
        parent::__construct($router);
        $this->order = new ApplicationOrder();
    }

    /**
     * Requere a página de confirmação do pedido.
     * Require order confirmation page.
     */
    public function confirmation()
    {
        echo $this->view->render($this->order->dirApp() . '/confirmation.php', 
            [
                'goToUrl' => $this->order->verifyIncorrectAccess('confirmation'),
                'session' => $this->order->payment(),
            ]);
    }

    /**
     * Limpa sessão com os dados de pedido de compra do usuário.
     * Clears session with user purchase order data.
     */
    public function clearOrder()
    {
        $this->order->clear();
    }

}
