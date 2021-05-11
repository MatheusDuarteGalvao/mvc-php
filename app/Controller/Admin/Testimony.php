<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page{

    /**
     * Método responsável por obter a renderização dos items de depoimentos para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
    */
    private static function getTestimonyItems($request,&$obPagination){
        //DEPOIMENTOS
        $items = '';

        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityTestimony::getTestimonies(null, null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

        //PÁGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //INSTÂNCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal,$paginaAtual,5);

        //RESULTADOS DA PÁGINA
        $results = EntityTestimony::getTestimonies(null,'id DESC',$obPagination->getLimit());

        //RENDERIZA O ITEM
        while($obTestimony = $results->fetchObject(EntityTestimony::class)){
            $items .= View::render('admin/modules/testimonies/item',[
                'id'        => $obTestimony->id,
                'nome'      => $obTestimony->nome,
                'mensagem'  => $obTestimony->mensagem,
                'data'      => date('d/m/Y H:i:s' , strtotime($obTestimony->data))
            ]);
        }

        //RETORNA OS DEPOIMENTOS
        return $items;
    }

    /**
     * Método responsável por por renderizar a view de listagem de depoimentos
     * @param Request $request
     * @return string
     */
    public static function getTestimonies($request){
        //CONTEÚDO DA HOME
        $content = View::render('admin/modules/testimonies/index',[
            'items'      => self::getTestimonyItems($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Depoimentos > Admin',$content, 'testimonies');
    }
}