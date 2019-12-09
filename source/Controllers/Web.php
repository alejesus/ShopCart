<?php

namespace Source\Controllers;

use Source\Controllers\Controller;

/**
 * Classe responsável por controlar a rota das páginas
 */
class Web extends Controller
{
    /**
     * Construtor herda tratamento de Url da Classe Controller
     * e após verificar se o método da página solicitada existe
     * o método é chamado para fornecer a página requisitada.
     */
    public function __construct($router)
    {
        parent::__construct($router);
        // var_dump(ROOT . '/themes/order');
    }

    /**
     * Inicializa a página home do site.
     * Initializes the home page of the site.
     **/
    public function home($products)
    {
        // var_dump($this->path, $this->urlMethod, $this->url);
        // var_dump($this->method);
        // var_dump($this->path . $this->urlMethod . '.php');

        // $products          = (new Product)->listProducts();
        // $_POST['products'] = $products;

        // if (!$this->verifyPath($this->path . $this->urlMethod . '.php')) {
        // $_POST['products'] = null;
        // return;
        // }

    }

    /**
     * Inicializa a página de contatos do site.
     * Initializes the contacts page of the site.
     **/
    public function contact()
    {

        $this->verifyPath($this->path . $this->urlMethod . '.php');

    }

    /**
     * Inicializa a página sobre do site.
     * Initializes the about page of the site.
     **/
    public function about()
    {

        $this->verifyPath($this->path . $this->urlMethod . '.php');

    }

    public function order(): void
    {
        if (!empty($_SESSION['cart'])) {
            var_dump($_SESSION['cart']);
        } else {
            var_dump(false);
        }

        echo '<a href="' . HOME . '" title="">Voltar</a>';

    }

}
