<?php

namespace Source\core;

/**
 * Classe responsável pelo tratamento de todas as páginas do sistema.
 * Class responsible for handling all system pages.
 */
class Router
{

    protected $url;
    protected $route;
    protected $method;
    protected $urlMethod;
    protected $params = [];
    protected $path;
    private $group;
    private $controllerDispatch;
    private $methodDispatch;
    private $paramsDispatch;
    private $error;

    /**
     * Recebe e trata a url da requisição atual ao instanciar a classe.
     * Define as variáveis path, url e urlMethod globalmente.
     * Receives and handles the url of the current request when instantiating the class.
     * Defines the path, url, and urlMethod variables globally.
     **/
    public function __construct(string $path = null)
    {

        $this->path      = (!empty($path) ? $path . '/' : dirname(ROOT, 1) . '/themes/');
        $getUrl          = strip_tags(trim(filter_input(INPUT_GET, 'url', FILTER_DEFAULT)));
        $setUrl          = (!empty($getUrl) ? $getUrl : 'index');
        $this->url       = explode('/', $setUrl);
        $this->urlMethod = ($this->url[0] == 'index' ? 'home' : $this->url[0]);

    }

    /**
     * Gera a url necessária para fazer a requisição e ativar os
     * controladores correspondentes. É geralmente atribuida em href links '<a></a>'
     * Generates the url required to make the request and activate the corresponding controllers.
     * It is usually assigned to href links '<a></a>'
     * @param string $triggers
     * @param array|null $params
     * @param string|null $group
     * @return string
     */
    public function route(string $triggers, array $params = null, string $group = null): string
    {

        $actions    = explode('.', $triggers);
        $controller = $actions[0];
        $method     = (!empty($actions[1]) ? '/' . $actions[1] : '');
        $params     = (!empty($params) ? '/' . implode('/', array_values($params)) : '');

        if ($controller == 'web') {

            return HOME . $method . $params;

        }

        if (!empty($group)) {

            return HOME . '/' . $group . '/' . $controller . $method . $params;

        }

        return HOME . '/' . $controller . $method . $params;

    }

    /**
     * Recupera o grupo de urls do sistema a serem acionadas
     * (Web, Ecommerce, Cart, Blog, Admin, etc).
     * Retrieves the group of system urls to be triggered
     * (Web, Ecommerce, Cart, Blog, Admin, etc).
     * @param string|null $group
     * @return void
     */
    public function group(string $group = null): void
    {

        $this->group = $group;
        $urls        = $this->url;

        if (empty($this->group) && $urls[0] != 'index') {
            $this->route  = 'home';
            $this->method = $urls[0];
            unset($urls[0]);
            $this->params = (!empty($urls) ? $urls : []);

        } else {

            $this->route = ($urls[0] == 'index' ? 'home' : $urls[0]);
            unset($urls[0]);
            $this->method = (!empty($urls[1]) ? $urls[1] : '');
            unset($urls[1]);
            $this->params = (!empty($urls) ? $urls : []);

        }

    }

    /**
     * Verifica se o grupo de urls requisitado na url é compatível com os grupos
     * de urls disponíveis para uso no sistema.
     * Checks whether the url group requested in the url is compatible with the
     * url groups available for use in the system.
     * @return bool
     */
    private function verifyGroup(): bool
    {
        if (!isset($this->group) && $this->route != 'home') {
            return false;
        }

        if (!empty($this->group) && $this->route == 'home') {
            return false;
        }

        if (!empty($this->group) && $this->route != substr($this->group, 1)) {
            return false;
        }

        return true;
    }

    /**
     * Executa, verifica e trata as requisições feitas através das urls.
     * Execute, verify and handle requests made via urls.
     * @param string $subUrl
     * @param string $triggers
     * @param string|null $nickname
     * @return type
     */
    private function requests(string $subUrl, string $triggers, string $nickname = null)
    {

        if (!$this->verifyGroup()) {
            return;
        }

        if (empty($subUrl) || empty($triggers)) {
            $this->error = 'fill the required params!';
            return;
        }

        $subLink = ($subUrl != '/' ? (strpos($subUrl, '/', 2) !== false ? strpos($subUrl, '/', 2) - 1 : strlen($subUrl)) : '');
        $link    = ($subUrl == '/' ? '' : substr($subUrl, 1, $subLink));

        if ($link != $this->method) {
            return;
        }

        $dinamicParam = (strpos($subUrl, '{'));

        $params = [];
        $page   = null;

        if ($subUrl != '/') {
            $subUrl = explode('/', substr($subUrl, 1));
            $page   = $subUrl[0];
            unset($subUrl[0]);
            $params = $subUrl;
        } else {
            $page = '';
        }

        $controller = explode(':', $triggers);
        $namespace  = '\\Source\Controllers\\' . $controller[0];
        $object     = new $namespace($this);

        if ($page != $this->method || ($dinamicParam === false && array_diff($params, $this->params) != [])) {

            $this->error = 'Urls do not match!';
            return false;
        }

        if (!method_exists($object, $controller[1])) {
            $this->error = 'Method Not Exists';
            return false;
        }

        $params = ($dinamicParam !== false ?
            array_combine(array_values($this->clearParams($params)), array_values($this->params)) :
            $params);

        $this->controllerDispatch = $object;
        $this->methodDispatch     = $controller[1];
        $this->paramsDispatch     = $params;

    }

    /**
     * Chama a execução, verifição e tratamento de requisições somente para método POST.
     * Calls execution, verification and handling of requests for POST method only.
     * @param string $subUrl
     * @param string $triggers
     * @param string|null $nickname
     * @return void
     */
    public function post(string $subUrl, string $triggers, string $nickname = null): void
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->requests($subUrl, $triggers);
        }

    }

    /**
     * Chama a execução, verifição e tratamento de requisições somente para método GET.
     * Calls execution, verification and handling of requests for POST method only.
     * @param string $subUrl
     * @param string $triggers
     * @param string|null $nickname
     * @return void
     */
    public function get(string $subUrl, string $triggers, string $nickname = null): void
    {

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $this->requests($subUrl, $triggers);
        }

    }

    /**
     * Recupera as mensagens de erro da classe, caso haja algum problema.
     * Retrieves class error messages if there are any problems.
     * @return string|null
     */
    public function error():  ? string
    {

        return $this->error;

    }

    /**
     * Libera a execução de todos os rotas do sistema, chamando
     * o respectivo controller e método de cada requisição.
     * Releases the execution of all system routes by calling
     * the respective controller and method of each request.
     * @return void
     */
    public function dispatch() : void
    {

        if (!empty($this->controllerDispatch) && !empty($this->methodDispatch) && isset($this->paramsDispatch)) {
            call_user_func_array([$this->controllerDispatch, $this->methodDispatch], [$this->paramsDispatch]);
        } else {
            $this->error = 'No one compatible requisition!';
        }

    }

    /**
     * Limpa os parâmetros dinâmicos, retirando os colchetes ({})
     * que vem com eles chamar a requisição.
     * Clears the dynamic parameters by removing the brackets ({})
     * that come with them calling the request.
     * @param array $params
     * @return type
     */
    private function clearParams(array $params)
    {

        if (!empty($params)) {

            $params = str_replace(['{', '}'], '', $params);
            return $params;

        }
        return;
    }

}
