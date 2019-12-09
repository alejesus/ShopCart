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
    public function ajaxMessage(string $message, string $type): string
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
     * Valida e trata os dados de entrada fornecidos pelo usuário.
     * Validates and handles user-supplied input data.
     * @param array $data
     * @return array|null
     */
    protected function validateData(array $data) :  ? array
    {
        if (in_array('', $data)) {
            throw new Exception($this->ajaxMessage('Preencha os campos obrigatórios!', 'warning'));
        }

        return $data;
    }
}
