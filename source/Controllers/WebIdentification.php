<?php

namespace Source\Controllers;

use Source\Facades\ApplicationOrder;
use Source\Facades\Identification;
use Source\Models\Cart;
use Source\Models\User;

/**
 * Classe responsável pela fase de identificação/cadastro do usuário
 * para realizar uma compra no sistema.
 * Class responsible for the user identification / registration phase
 * to make a purchase in the system.
 */
class WebIdentification extends Controller
{
    use SaveCartTrait;

    private $order;
    private $identification;
    private $error;

    /**
     * Inicializa a herança da Classe Controller e os Facades Order e Identification.
     * Initializes Controller Class inheritance and Order and Identification Facades.
     **/
    public function __construct($router)
    {
        parent::__construct($router);
        $this->order          = new ApplicationOrder();
        $this->identification = new Identification();
    }

    /**
     * Exibe em formato jSon a sessão de usuário atual.
     * Displays in jSon format the current user session.
     * @return void
     **/
    public function showIdentification(): void
    {
        echo json_encode($this->identification->identification());
    }

    /**
     * Retorna a página de indetificação (login/cadastro) do usuário.
     * Returns the user's login page.
     * @return void
     **/
    public function login(): void
    {
        echo $this->view->render($this->order->dirApp() . '/login/index.php',
        ['goToUrl' => $this->order->verifyIncorrectAccess('identification')]);
    }

    /**
     * Retorna a página de indetificação (login/cadastro) do usuário.
     * Returns the user's login page.
     * @return void
     **/
    public function register(): void
    {
        echo $this->view->render($this->order->dirApp() . '/login/signup.php',
        ['goToUrl' => $this->order->verifyIncorrectAccess('identification')]);
    }

    /**
     * Retorna a página de indetificação (login/cadastro) do usuário.
     * Returns the user's login page.
     * @return void
     **/
    public function recover(): void
    {
        echo $this->view->render($this->order->dirApp() . '/login/recover.php', 
        ['goToUrl' => $this->order->verifyIncorrectAccess('identification')]);
    }

    /**
     * Logar usuário ao sistema.
     * Login user to the system.
     * @return void
     **/
    public function signIn(array $data = null): void
    {

        $data = $this->filterPostRequest();

        $next = $data['next'];
        unset($data['next']);

        $data = $this->validateData($data);

        $user = (new User)->find("email = :email", "email={$data['email']}")->fetch();

        if (!$user || $user->pass != $data['pass']) {
            $this->error   = 'Email ou Senha incorretos!';
            $jSon['error'] = $this->error;
            echo json_encode($jSon);
            return;
        }

        $cartId = $user->id;

        $this->order->addCart($cartId);

        $this->identification->signIn($user);

        $this->order->addIdentification();

        $this->nextStep($next);

    }

    /**
     * Cadastra novo usuário ao sistema.
     * Register new user to the system.
     * @return void
     **/
    public function signUp(array $data = null): void
    {

        $data = $this->filterPostRequest();

        $next = $data['next'];
        unset($data['next']);

        $data = $this->validateData($data);

        $dataCart     = $this->order->cart();
        $dataShipment = ($this->order->shipment() ?? null);

        $userId = $this->saveUserDB($data);

        $cartId = $this->saveCartDB($userId, $dataCart, $dataShipment);
        $this->saveCartItemDB($cartId, $dataCart);

        if ($this->error) {
            $jSon['error'] = $this->error;
            echo json_encode($jSon);
            return;
        }

        $this->order->addCart($cartId);

        $this->identification->signUp((new User)->findById($userId));

        $this->order->addIdentification();

        $this->nextStep($next);

    }

    /**
     * Cadastra novo usuário na base de dados.
     * Register new user in the database.
     * @param array $data
     * @return int|null
     */
    private function saveUserDB(array $data):  ? int
    {

        $newUser             = new User();
        $newUser->name       = $data['name'];
        $newUser->pass       = $data['pass'];
        $newUser->email      = $data['email'];
        $newUser->cpf        = $data['cpf'];
        $newUser->genre      = $data['genre'];
        $newUser->birthdate  = $data['birthDate'];
        $newUser->phone      = $data['phone'];
        $newUser->whatsapp   = $data['whatsApp'];
        $newUser->newsletter = $data['newsletter'];
        $idUser              = $newUser->save();

        if ($newUser->fail()) {
            $this->error = $newUser->fail()->getMessage();
            return null;
        }

        return $idUser;

    }

    /**
     * Realiza o processo de recuperação de nova senha para o usuário que esqueceu suas credenciais.
     * Performs the new password recovery process for the user who has forgotten their credentials.
     * @param array|null $data
     * @return void
     */
    public function forgetLogin(array $data = null) : void
    {

    }

    /**
     * Redireciona o precesso de compra para a próxima etapa.
     * Directs the buying process to the next step.
     * @param string|null $next
     * @return void
     */
    public function nextStep(string $next = null): void
    {

        $jSon['url'] = (!empty($next) ? $this->order->previousUrlIdentification($next) :
            (empty($this->order->cart()) ? $this->order->nextStepCart() :
                $this->order->nextStepAddress()));

        echo json_encode($jSon);
    }

    /**
     * Valida e trata os dados de entrada fornecidos pelo usuário.
     * Validates and handles user-supplied input data.
     * @param array $data
     * @return array|null
     */
    private function validateData(array $data):  ? array
    {

        if (in_array('', $data)) {
            $this->error = $this->ajaxMessage('Preencha os campos obrigatórios!', 'error');
            echo json_encode($this->error);
            return null;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error = $this->ajaxMessage('Email inválido!', 'error');
            echo json_encode($this->error);
            return null;
        }

        // Colocar validador de CPF depois

        $data['pass'] = md5($data['pass']);

        if ($data['action'] == 'signup') {

            $data['name']       = ucwords(strtolower($data['name']));
            $data['cpf']        = str_replace(['.'], '', $data['cpf']);
            $data['phone']      = str_replace(['(', ')', '-'], '', $data['phone']);
            $data['whatsApp']   = (!empty($data['whatsApp']) ? intval($data['whatsApp']) : 0);
            $data['newsletter'] = (!empty($data['newsletter']) ? intval($data['newsletter']) : 0);
            $data['birthDate']  = date(DATE_W3C, strtotime($data['birthDate']));

        }

        return $data;

    }

}
