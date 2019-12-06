<?php

namespace Source\Controllers;

use Source\Controllers\Controller;
use Source\Facades\ApplicationCart;
use Source\Facades\ApplicationOrder;
use Source\Facades\Identification;
use Source\Models\Cart;
use Source\Models\Product;
use Exception;

/**
 * Classe responsável pelas interações com o carrinho de compras.
 * Class responsible for shopping cart interactions.
 */
class WebCart extends Controller
{
    use SaveCartTrait;

    private $order;
    private $cart;
    private $error;

    /**
     * Inicializa configurações da classe Controller
     * e instancia o facade com a regra de negócio necessária
     * para a manipulação do carrinho.
     * Initializes Controller class settings and instantiates
     * the facade with the business rule required for cart handling.
     **/
    public function __construct($router)
    {
        parent::__construct($router);
        $this->order          = new ApplicationOrder();
        $this->cart           = new ApplicationCart();
        $this->identification = new Identification();

    }

    /**
     * Exibe a página do carrinho de compras.
     * Displays the shopping cart page.
     * @return void
     */
    public function home(): void
    {
        $products = (new Product)->find()->fetch(true);

        echo $this->view->render($this->order->dirApp() . '/home', ['products' => $products]);
    }

    /**
     * Exibe os dados existentes na sessão de carrinho em json
     * Displays existing data in json cart session
     **/
    public function cart(array $data): void
    {
        echo json_encode($this->cart->cart());
    }

    /**
     * Adiciona um item para o carrinho de compras
     * Add an item to the shopping cart.
     **/
    public function add(array $data): void
    {
        $id      = (!empty($data['id']) ? filter_var($data['id'], FILTER_VALIDATE_INT) : 0);
        $product = (new Product)->findById($id);

        if (empty($id) || empty($product)) {
            $jSon['error'] = $this->ajaxMessage('Erro ao adicionar o produto', 'error');
            echo json_encode($jSon);
            return;
        }

        echo json_encode($this->cart->add($product)->cart());
    }

    /**
     * Remove um item para o carrinho de compras
     * Remove an item to the shopping cart.
     **/
    public function remove(array $data): void
    {
        $id      = (!empty($data['id']) ? filter_var($data['id'], FILTER_VALIDATE_INT) : 0);
        $product = (new Product)->findById($id);

        if (empty($id) || empty($product)) {
            $jSon['error'] = $this->ajaxMessage('Erro ao remover o produto', 'error');
            echo json_encode($jSon);
            return;
        }

        echo json_encode($this->cart->remove($product)->cart());
    }

    /**
     * Limpa o carrinho de compras.
     * Clears the shopping cart.
     **/
    public function clear(): void
    {
        echo json_encode($this->cart->clear()->cart());
    }

    /**
     * Conclui carrinho e retorna via json a url da próxima etapa do pedido.
     * Complete cart and return via json to url for the next order step.
     **/
    public function finishCart()
    {
        if(empty($this->cart->cart())){
            $jSon['error'] = $this->ajaxMessage('Adicione algum produto ao carrinho', 'info');
            echo json_encode($jSon);
            return;
        }

        try {

            if (!$this->identification->identification()) {

                $this->order->addCart();

            } else {

                $userId = $this->identification->identification()['id'];
                $dataCart     = $this->cart->cart();
                $dataShipment = ($this->order->shipment() ?? null);

                $cartId = $this->saveCartDB($userId, $dataCart, $dataShipment);
                $this->order->addCart($cartId);
            }
            
        } catch (Exception $e) {
            $jSon['error'] = $e->getMessage();
            echo json_encode($jSon);
            return;
        }

        $jSon['url'] = $this->order->nextStepIdentification();

        echo json_encode($jSon);
    }
}