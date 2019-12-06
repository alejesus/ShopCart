<?php

namespace Source\Controllers;

use Source\Facades\ApplicationOrder;
use Source\Facades\PaymentCart;
use Source\Models\Cart;
use Source\Models\Order;
use Source\Models\OrderItem;
use Source\Support\Payment;
use Exception;

/**
 * Classe responsável pela manipulação, tratamento e
 * requisição de pagamento dos pedidos.
 * Class responsible for handling, processing and payment requests.
 */
class WebPayment extends Controller
{

    private $order;
    private $payment;
    private $supportPay;
    private $error;

    /**
     * Inicializa os objetos e classes herdadas necessárias para a classe.
     * Initialize the inherited objects and classes required for a class.
     **/
    public function __construct($router)
    {
        parent::__construct($router);
        $this->order      = new ApplicationOrder();
        $this->payment    = new PaymentCart();
        $this->supportPay = new Payment();
    }

    /**
     * Requere a página de pagamento do sistema.
     * Require system payment page.
     * @return void
     */
    public function payment(): void
    {
        echo $this->view->render($this->order->dirApp() . '/payment.php', [
            'goToUrl' => $this->order->verifyIncorrectAccess('payment'),
            'carts' => $this->order->cart(),
            'user' => $this->order->user(),
            'address' => $this->order->address(),
            'shipping' => $this->order->shipment(),
        ]);
    }

    /**
     * Exibe a sessão de pagamento atual do sistema.
     * Displays the current payment session of system.
     * @return void
     */
    public function showSession(): void
    {
        echo json_encode($this->payment->payment());
    }

    /**
     * Inicializa a sessão da API de pagamento atual.
     * Initializes the current Payment API session.
     * @return type
     */
    public function initSessionPay(): void
    {
        $this->supportPay->createSession();
        $response = json_decode(json_encode(simplexml_load_string($this->supportPay->callback())), true);
        echo json_encode($response['id']);
    }

    /**
     * Realiza o pagamento via Cartão de Crédito.
     * Make payment via Credit Card.
     * @return void
     */
    public function withCreditCard(): void
    {
        $data = $this->filterPostRequest();

        try {

            $data = $this->validateData($data);

            $this->supportPay->withCard($data);
            $response = json_decode(json_encode(simplexml_load_string($this->supportPay->callback())), true);

            if (isset($response['error'])) {
                $jSon['error'] = $this->ajaxMessage('Erro na API de pagamento', 'error');
                echo json_encode($jSon);
                return;
            }

            $this->updateCart();
            $orderId = $this->saveOrderDB($data, $response);
            $newOrder = (new Order())->findById($orderId);

            $this->payment->add($newOrder);

            $this->order->addPayment();

        } catch (Exception $e) {
            $jSon['error'] = $e->getMessage();
            echo json_encode($jSon);
            return;
        }

        $this->nextStep();
    }

    /**
     * Realiza o pagamento via Débito Online.
     * Make the payment via Debit Online.
     * @return void
     */
    public function withOnlineDebt(): void
    {
        $data = $this->filterPostRequest();

        try {

            $data = $this->validateData($data);

            $this->supportPay->withOnlineDebt($data);
            $response = json_decode(json_encode(simplexml_load_string($this->supportPay->callback())), true);

            if (isset($response['error'])) {
                $jSon['error'] = $this->ajaxMessage('Erro na API de pagamento', 'error');
                echo json_encode($jSon);
                return;
            }

            $this->updateCart();
            $orderId = $this->saveOrderDB($data, $response);
            $newOrder = (new Order())->findById($orderId);

            $this->payment->add($newOrder);

            $this->order->addPayment();
            
        } catch (Exception $e) {
            $jSon['error'] = $e->getMessage();
            echo json_encode($jSon);
            return;
        }

        $this->nextStep();
    }

    /**
     * Realiza o pagamento via Boleto Bancário.
     * Make the payment via bank slip.
     * @return void
     */
    public function withBillet(): void
    {

        $data = $this->filterPostRequest();

        try {

            $data = $this->validateData($data); 

            $this->supportPay->withBillet($data);
            $response = json_decode(json_encode(simplexml_load_string($this->supportPay->callback())), true);

            if (isset($response['error'])) {
                $jSon['error'] = $this->ajaxMessage('Erro na Api de pagamento!', 'error');
                echo json_encode($jSon);
                return;
            }

            $this->updateCart();
            $orderId = $this->saveOrderDB($data, $response);
            
            $newOrder = (new Order())->findById($orderId);

            $this->payment->add($newOrder);

            $this->order->addPayment();
            
        } catch (Exception $e) {
            $jSon['error'] = $e->getMessage();
            echo json_encode($jSon);
            return;
        }

        $this->nextStep();
    }

    /**
     * Redireciona a para a página da etapa seguinte.
     * Redirect to to the next step page.
     * @return void
     */
    public function nextStep(): void
    {
        $jSon['url'] = $this->order->nextStepConfirmation();
        echo json_encode($jSon);
    }

    /**
     * Atualiza carrinho com as informações de frete.
     * Updates cart with shipping information.
     * @return void
     */
    private function updateCart(): void
    {
        if(!$this->order->shipment()){
            return;
        }

        $id       = $this->order->cart()['id'];
        $shipment = $this->order->shipment();

        $cart = (new Cart)->findById($id);

        $cart->shipment_type     = $shipment['type'];
        $cart->shipment_value    = $shipment['value'];
        $cart->shipment_deadline = $shipment['deadline'];

        $cart->save();

        if ($cart->fail()) {
            throw new Exception($this->ajaxMessage($cart->fail()->getMessage(), 'error'));
        }
    }

    /**
     * Salva os dados do pedido para no BD.
     * Saves the order data to the DB.
     * @param array $responseApi
     * @return int|null
     */
    private function saveOrderDB(array $data, array $responseApi):  ? int
    {
        $user     = $this->order->user();
        $shipment = $this->order->shipment();

        $order                     = new Order();
        $order->transaction        = $responseApi['code'];
        $order->reference          = $responseApi['reference'];
        $order->user_id            = $user->id;
        $order->fee_amount         = $responseApi['feeAmount'];
        $order->net_amount         = $responseApi['netAmount'];
        $order->extra_amount       = $responseApi['extraAmount'];
        $order->gross_amount       = $responseApi['grossAmount'];
        $order->payment_method     = $responseApi['paymentMethod']['type'];
        $order->payment_link       = (!empty($responseApi['paymentLink']) ? $responseApi['paymentLink'] : null);
        $order->installments       = $responseApi['installmentCount'];
        $order->installments_value = ($data['installmentValue'] ?? $responseApi['grossAmount']);
        $order->total_amount       = ($data['totalCart'] ??
            ($responseApi['paymentMethod']['type'] == 2 ? $responseApi['grossAmount'] + 1 : $responseApi['grossAmount']));
        $order->status            = $responseApi['status'];
        $order->shipment_type     = $shipment['type'];
        $order->shipment_value    = $responseApi['shipping']['cost'];
        $order->shipment_deadline = $shipment['deadline'];
        $id                       = $order->save();

        if ($order->fail()) {
            throw new Exception($this->ajaxMessage($order->fail()->getMessage(), 'error'));
        }

        $this->saveOrderItemsDB($id);

        return $id;
    }

    /**
     * Salva os itens do pedido na base de dados.
     * Saves the order items to the database.
     * @param int $orderId
     * @return void
     */
    private function saveOrderItemsDB(int $orderId) : void
    {
        $items = $this->order->cart()['items'];

        foreach ($items as $item) {

            $itemOrder = new OrderItem();

            $itemOrder->order_id   = $orderId;
            $itemOrder->product_id = $item['id'];
            $itemOrder->price      = $item['price'];
            $itemOrder->quantity   = $item['amount'];
            $itemOrder->subtotal   = $item['subtotal'];
            $itemOrder->discount   = $item['discount'];
            $itemOrder->total      = $item['total'];

            $itemOrder->save();

            if ($itemOrder->fail()) {
                throw new Exception($this->ajaxMessage($itemOrder->fail()->getMessage(), 'error'));
            }
        }
    }

    private function validateData(array $data): ?array
    {
        if (in_array('', $data)) {
            throw new Exception($this->ajaxMessage('Preencha os campos obrigatórios!', 'warning'));
        }

        if($data['paymentMethod'] == 'creditCard'){
            $data['installmentValue'] = number_format($data['installmentValue'], 2, '.', '');    
        }

        return $data;
    }

}
