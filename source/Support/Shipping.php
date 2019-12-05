<?php

namespace Source\Support;

/**
 * SUPPORT
 * Classe responsável pela manipulação de API's de Frete (ex: Correios)
 * Class responsible for handling Freight APIs (eg Correios)
 */
class Shipping
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
        $this->apiUrl = API_SHIPPING_URL;
        $this->apiKey = API_SHIPPING_KEY;
    }

    /**
     * Método responsável por retornar os dados de frete para entrega.
     * Method responsible for returning freight data for delivery.
     * @return void
     **/
    public function byPriceDeadline(array $build): void
    {

        $this->endpoint = API_SHIPPING_ENDPOINT_PRECO_PRAZO;
        $this->build    = $build;
        $this->post();
    }

    /**
     * Método responsável pela comunicação e obtenção de dados da API.
     * Method responsible for communicating and obtaining API data.
     * @return void
     **/
    private function post(): void
    {

        $data = (!empty($this->apiKey) ? array_merge($this->apiKey, $this->build) : $this->build);
        $url  = $this->apiUrl . $this->endpoint . '?' . http_build_query($data);

        $channel = curl_init($url);
        curl_setopt($channel, CURLOPT_POST, true);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($channel, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($channel, CURLOPT_HTTPHEADER, []);
        $this->callback = curl_exec($channel);
        curl_close($channel);

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

}
