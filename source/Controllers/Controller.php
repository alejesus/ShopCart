<?php

namespace Source\Controllers;

use League\Plates\Engine;
use Source\core\Router;

/**
 * Classe Responsável por Controlar as Rotas, redirecionamentos e
 * mensagens de erro padrão para todos os controladores.
 * Class Responsible for Tracking Routes, Redirects and
 * standard error messages for all controllers.
 */
abstract class Controller
{
    protected $view;
    protected $router;

    /**
     * Inicializa e herda as configurações básicas do sistema.
     * Initializes and inherits the basic system settings.
     **/
    public function __construct($router, string $path = null)
    {
        $this->router = $router;
        
        $this->view = Engine::create(dirname(ROOT, 1) . '/themes/', 'php');
        $this->view->addData(['router' => $this->router]);
    }

    /**
     * Exibe mensagem de erro padrão do sistema em ajax.
     * Displays standard system error message in ajax.
     **/
    public function ajaxMessage(string $message, string $type) : string
    {
        return "<div class=\"message {$type}\">{$message}</div>";
    }

    /**
     * Filtra os dados enviados via formulário.
     * Filters the data submitted via the form.
     * @return array|null
     */
    protected function filterPostRequest():  ? array
    {
        $getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $setPost = array_map('strip_tags', $getPost);
        $Post    = array_map('trim', $setPost);

        return $Post;
    }

    /**
     * Verifica se o arquivo requisitado pela rota existe.
     * Caso não existe, o usuário será redirecionado para a página de erro 404
     * Checks if the file requested by the route exists.
     * If not, user will be redirected to error page 404.
     **/
    protected function verifyPath($path): bool
    {
        if (!file_exists($path)) {
            $this->callBack();
            return false;
        }

        require $path;

        return true;
    }

    /**
     * Exibe página de erro 404.
     * 404 Error Page Displays.
     **/
    protected function callBack()
    {
        echo 'Erro 404';
    }
}
