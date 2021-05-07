<?php

namespace App\Controller\Pages;

use App\Model\Entity\Organization;
use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;

class Testimony extends Page{

    /**
     * Método responsável por obter a renderização dos items de depoimentos para a página
     * @return string
    */
    private static function getTestimonyItems(){
        //DEPOIMENTOS
        $items = '';

        //RESULTADOS DA PÁGINA
        $results = EntityTestimony::getTestimonies(null, 'id DESC');

        //RENDERIZA O ITEM
        while($obTestimony = $results->fetchObject(EntityTestimony::class)){
            $items .= View::render('pages/testimony/item',[
                'nome'      => $obTestimony->nome,
                'mensagem'  => $obTestimony->mensagem,
                'data'      => date('d/m/Y H:i:s' , strtotime($obTestimony->data))
            ]);
        }

        //RETORNA OS DEPOIMENTOS
        return $items;
    }

    /**
     * Método responsável por retornar o conteúdo (view) de depoimentos 
     * @return string
     */
    public static function getTestimonies(){
        $obOrganization  = new Organization;

        //VIEW DE DEPOIMENTOS
        $content = View::render('pages/testimonies', [
            'items' => self::getTestimonyItems()
        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage('DEPOIMENTOS > Matheus Duarte', $content);
    }

    /**
     * Método responsável por cadastrar um depoimento
     * @param Request $request
     * @return string
     */
    public static function insertTestimony($request){
        //DADOS DO POST
        $postVars = $request->getPostVars();

        //NOVA INSTÂNCIA DE DEPOIMENTO
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();

        return self::getTestimonies();
    }
}