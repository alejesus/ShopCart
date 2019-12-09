<?php

namespace Source\Facades;

use Source\Models\User;

/**
 * Classe responsável pela regra de negócio da
 * identificação(cadastro/login) do usuário.
 * Class responsible for the business rule of
 * user identification (registration / login).
 */
class Identification
{
    /**
     * Inicializa sessão de usuário caso ainda não exista.
     * Initializes user session if it does not already exist.
     **/
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION['user'] = (!empty($_SESSION['user']) ? $_SESSION['user'] : []);
    }

    /**
     * Retorna a sessão de usuário criada após a identificação.
     * Returns the user session created after identification.
     * @return array|null
     **/
    public function identification()
    {
        return $_SESSION['user'];
    }

    /**
     * Atribui a sessão de usuário os dados cadastrados no cadastro de usuários.
     * Assigns the user session the data registered in the user register.
     * @return Identification
     **/
    public function signUp(User $data): Identification
    {
        $_SESSION['user'] = $data->data();

        return $this;
    }

    /**
     * Atribui a sessão de usuário os dados do usuário buscados no BD.
     * Assigns the user session the user data fetched from the DB.
     * @return Identification
     **/
    public function signIn(User $data): Identification
    {
        $_SESSION['user'] = $data->data();

        return $this;
    }

    /**
     * Recupera uma nova senha para o usuário.
     * Retrieves a new password for the user.
     * @return type
     **/
    public function forgetLogin()
    {

    }

}
