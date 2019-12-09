<?php

namespace Source\Facades;

/**
 * Classe responsável pela regra de negócio do sistema de pedidos.
 * Class responsible for the ordering business rule.
 */
class ApplicationOrder
{
    private $dirApp;
    private $urlApp = HOME . '/order/';

    /**
     * Incicializa sessão de pedidos e diretório da aplicação.
     * Initialize request session and application directory.
     */
    public function __construct()
    {
        $this->dirApp = 'orderSystem';

        if (!session_id()) {
            session_start();
        }

        $_SESSION['order'] = (!empty($_SESSION['order']) ? $_SESSION['order'] : []);
        // unset($_SESSION['order'], $_SESSION['cart'], $_SESSION['user'], $_SESSION['address'], $_SESSION['shipping']);
    }

    /**
     * Retorna o diretório da aplicação.
     * Returns the application directory.
     * @return string
     */
    public function dirApp(): string
    {
        return $this->dirApp;
    }

    /**
     * Retorna a sessão do pedido iniciado..
     * Returns the session of the started request.
     * @return array|null
     */
    public function showOrder():  ? array
    {
        return $_SESSION['order'];
    }

    /**
     * Retorna a sessão do carrinho.
     * Returns the cart session.
     * @return type
     */
    public function cart()
    {
        return ($_SESSION['order']['cart'] ?? []);
    }

    /**
     * Retorna a sessão do frete.
     * Returns the shipment session.
     * @return array|null
     */
    public function shipment() :  ? array
    {
        return ($_SESSION['order']['shipping'] ?? null);
    }

    /**
     * Retorna a sessão do usuário.
     * Returns the user session.
     * @return type
     */
    public function user()
    {
        return ($_SESSION['order']['user'] ?? []);
    }

    /**
     * Retorna a sessão do endereço do usuário.
     * Returns the user address session.
     * @return type
     */
    public function address()
    {
        return ($_SESSION['order']['address'] ?? []);
    }

    /**
     * Retorna a sessão de pagamento do pedido.
     * Returns the order pay session.
     * @return type
     **/
    public function payment()
    {
        return ($_SESSION['order']['payment'] ?? []);
    }

    /**
     * Adiciona carrinho a sessão de pedidos.
     * Adds cart to ordering session.
     * @return void
     **/
    public function addCart(int $id = null) : void
    {
        $_SESSION['order']['cart'] = $_SESSION['cart'];

        if ($id) {
            $_SESSION['order']['cart']['id'] = $id;
        }
    }

    /**
     * Adiciona informações do usuário a sessão de pedidos.
     * Adds user information to the ordering session.
     * @return void
     **/
    public function addIdentification(): void
    {
        $_SESSION['order']['user'] = $_SESSION['user'];
    }

    /**
     * Adiciona informações de entrega a sessão de pedidos.
     * Adds shipping information to the ordering session.
     * @return void
     **/
    public function addAddress(): void
    {
        $_SESSION['order']['address']  = $_SESSION['address'];
        $_SESSION['order']['shipping'] = $_SESSION['shipping'];
    }

    /**
     * Adiciona informações de pagamento a sessão de pedidos.
     * Adds payment information to the ordering session.
     * @return void
     **/
    public function addPayment(): void
    {
        $_SESSION['order']['payment'] = $_SESSION['payment'];
    }

    /**
     * Redireciona para a etapa de Carrinho de compras.
     * Redirects to the Shop Cart step.
     * @return string|null
     */
    public function nextStepCart():  ? string
    {
        return $this->urlApp;
    }

    /**
     * Redireciona para a etapa de Identificação do Usuário.
     * Redirects to the User Identification step.
     * @return string|null
     */
    public function nextStepIdentification() :  ? string
    {
        return $this->urlApp . 'login';
    }

    /**
     * Redireciona para a url que o usuário estava antes de realizar a identificação.
     * Redirects to the url that user have been before of to make the identification.
     * @return string|null
     */
    public function previousUrlIdentification(string $next) :  ? string
    {
        return $next;
    }

    /**
     * Redireciona para a etapa de Endereço de Entrega do pedido.
     * Redirects to the Shipping Address step of the order.
     * @return string|null
     */
    public function nextStepAddress() :  ? string
    {
        return $this->urlApp . 'address';
    }

    /**
     * Redireciona para a etapa de Pagamento do pedido.
     * Redirects to the Order Payment step.
     * @return string|null
     */
    public function nextStepPayment() :  ? string
    {
        return $this->urlApp . 'payment';
    }

    /**
     * Redireciona para a etapa de Confirmação do pedido.
     * Redirects to the Order Confirmation step.
     * @return string|null
     */
    public function nextStepConfirmation() :  ? string
    {
        return $this->urlApp . 'confirmation';
    }

    /**
     * Limpa as sessões com os dados do pedido de compra do usuário.
     * Clears sessions with user purchase order data.
     * @return void
     **/
    public function clear() : void
    {
        $_SESSION['order']    = [];
        $_SESSION['cart']     = [];
        $_SESSION['address']  = [];
        $_SESSION['shipping'] = [];
        $_SESSION['payment']  = [];
    }

    /**
     * Verifica qualquer acesso incorreto pelo usuário no fluxo de compra.
     * Checks for any incorrect user access in the purchase flow.
     * @param string $step
     * @return string|null
     **/
    public function verifyIncorrectAccess(string $step):  ? string
    {
        if (!VERIFYING_PURCHASE_FLOW) {
            return null;
        }

        switch ($step) {

            case 'identification' :

                if (!empty($_SESSION['user'])) {

                    $_SESSION['order']['user'] = $_SESSION['user'];

                    if (empty($_SESSION['order']['cart'])) {
                        return $this->urlApp;
                    }

                    return $this->urlApp . 'address';
                }

                return null;

                break;

            case 'address':

                if (empty($_SESSION['order']['cart'])) {
                    return $this->urlApp;
                }

                if (empty($_SESSION['order']['user'])) {
                    return $this->urlApp . 'identification';
                }

                return null;

                break;

            case 'payment':

                if (empty($_SESSION['order']['cart'])) {
                    return $this->urlApp;
                }

                if (empty($_SESSION['order']['user'])) {
                    return $this->urlApp . 'identification';
                }

                if (empty($_SESSION['order']['address']) || empty($_SESSION['order']['shipping'])) {
                    return $this->urlApp . 'address';
                }

                return null;

                break;

            case 'confirmation':

                if (empty($_SESSION['order']['cart'])) {
                    return $this->urlApp;
                }

                if (empty($_SESSION['order']['user'])) {
                    return $this->urlApp . 'identification';
                }

                if (empty($_SESSION['order']['address']) || empty($_SESSION['order']['shipping'])) {
                    return $this->urlApp . 'address';
                }

                if (empty($_SESSION['order']['payment'])) {
                    return $this->urlApp . 'payment';
                }

                return null;

                break;

            default:
                return null;
                break;
        }
    }
}
