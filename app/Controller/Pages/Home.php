<?php

namespace App\Controller\Pages;

use App\Model\Entity\Organization;
use \App\Utils\View;

class Home extends Page{

    /**
     * Método responsável por retornar o conteúdo (view) da nossa home
     * @return string
     */
    public static function getHome(){
        $obOrganization  = new Organization;

        //VIEW DA HOME
        $content = View::render('pages/home', [
            'name' => $obOrganization->name,
            'description' => $obOrganization->description,
            'site' => $obOrganization->site
        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage('Matheus Duarte - Home', $content);
    }
}