<?php
namespace Source\Support;

/**
 * SUPPORT
 * Classe responsável pela manipulação de API's de Pagamento (ex: Pagseguro, Paypal)
 * Class responsible for handling Payments APIs (eg Pagseguro, Paypal)
 */
class Payment
{
    private $apiUrl;
    private $apiKey;
    private $endpoint;
    private $build;
    private $callback;

    /**
     * Inicializa Url e Credenciais da API.
     * Initializes API Url and Credentials.
     **/
    public function __construct()
    {
        $this->apiUrl = API_PAYMENT_URL;
        $this->apiKey = API_PAYMENT_KEY;
    }

    /**
     * Gera a sessão da API de pagamento.
     * Generates the payment API session.
     * @return void
     **/
    public function createSession(): void
    {
        $this->endpoint = API_PAYMENT_ENDPOINT_SESSIONS;
        $this->post();
    }

    /**
     * Recupera os dados do cartão de crédito para salvar no BD.
     * Recovers credit card data for saving to BD.
     * @return void
     **/
    public function createCard(): void
    {

    }

    /**
     * Realiza pagamento via Cartão de Crédito.
     * Make payment via Credit Card.
     * @return void
     **/
    public function withCard(array $build): void
    {
        $this->endpoint = API_PAYMENT_ENDPOINT_TRANSACTIONS;
        $this->build    = $build;
        $this->post();
    }

    /**
     * Realiza pagamento via Débito Online.
     * Make payment via Online Debit.
     * @return void
     **/
    public function withOnlineDebt(array $build): void
    {
        $this->endpoint = API_PAYMENT_ENDPOINT_TRANSACTIONS;
        $this->build    = $build;
        $this->post();
    }

    /**
     * Realiza pagamento via Boleto Bancário.
     * MMake payment via bank slip.
     * @return void
     **/
    public function withBillet(array $build): void
    {
        $this->endpoint = API_PAYMENT_ENDPOINT_TRANSACTIONS;
        $this->build    = $build;
        $this->post();
    }

    /**
     * Retorna o resultado da comunicação com a API.
     * Returns the result of communicating with the API.
     * @return type
     **/
    public function callback()
    {
        return $this->callback;
    }

    /**
     * Método responsável pela comunicação e obtenção de dados da API.
     * Method responsible for communicating and obtaining API data.
     * @return void
     **/
    private function post(): void
    {
        $header = (!empty(HEADER_X) ? [HEADER_X] : []);
        $data   = (!empty($this->build) ? http_build_query($this->build) : '');
        $url    = $this->apiUrl . $this->endpoint . '?' . http_build_query($this->apiKey);

        $channel = curl_init($url);
        curl_setopt($channel, CURLOPT_POST, true);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($channel, CURLOPT_POSTFIELDS, $data);
        curl_setopt($channel, CURLOPT_HTTPHEADER, $header);
        $this->callback = (curl_error($channel) ? curl_error($channel) : curl_exec($channel));
        curl_close($channel);
    }
}
