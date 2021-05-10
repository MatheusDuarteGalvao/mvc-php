<?php

namespace App\Session\Admin;

class Login{

    /**
     * Método responsável por iniciar a sessão
     */
    private static function init(){
        //VERIFICA SE A SESSÃO NÃO ESTÁ ATIVA
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    /**
     * Método responável por criar o login do usuário
     * @param User $obUser
     * @return boolean
     */
    public static function login($obUser){
        //INICIA A SESSÃO
        self::init();

        //DEFINE A SESSÃO DO USUÁRIO
        $_SESSION['admin']['usuario'] = [
            'id'    => $obUser->id,
            'nome'  => $obUser->nome,
            'email' => $obUser->email
        ];

        //SUCESSO
        return true;
    }

}