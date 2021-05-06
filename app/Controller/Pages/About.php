<?php

namespace App\Controller\Pages;

use App\Model\Entity\Organization;
use \App\Utils\View;

class About extends Page{

    /**
     * Método responsável por retornar o conteúdo (view) da nossa página de sobre
     * @return string
     */
    public static function getAbout(){
        $obOrganization  = new Organization;

        //VIEW DA HOME
        $content = View::render('pages/about',[
            'name'          => $obOrganization->name,
            'description'   => $obOrganization->description,
            'site'          => $obOrganization->site
        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage('Matheus Duarte - Home', $content);
    }
}