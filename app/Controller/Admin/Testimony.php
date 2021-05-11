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
            'pagination' => parent::getPagination($request,$obPagination),
            'status'     => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Depoimentos > Admin',$content, 'testimonies');
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo depoimento
     * @param Request $request
     * @return string
     */
    public static function getNewTestimony($request){
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/testimonies/form',[
            'title'     => 'Cadastrar depoimento',
            'nome'      => '',
            'mensagem'  => '',
            'status'    => ''
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Cadastrar depoimento > Admin',$content, 'testimonies');
    }

    /**
     * Método responsável por cadastrar um depoimento no banco
     * @param Request $request
     * @return string
     */
    public static function setNewTestimony($request){
        //POST VARS
        $postVars = $request->getPostVars();

        //NOVA INSTÂNCIA DE DEPOIMENTO
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'] ?? '';
        $obTestimony->mensagem = $postVars['mensagem'] ?? '';
        $obTestimony->cadastrar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=created');
    }

    /**
     * Método responsável por retornar o formulário de edição
     * @param Request $request
     * @return string
     */
    private static function getStatus($request){
        //QUERY PARAMS
        $queryParams = $request->getQueryParams();

        //STATUS
        if(!isset($queryParams['status'])) return '';

        //MENSAGENS DE STATUS
        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Depoimento criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Depoimento atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Depoimento excluído com sucesso!');
                break;
        }
    }

    /**
     * Método responsável por retornar o formulário de edição de um depoimento
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getEditTestimony($request,$id){
        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTÂNCIA
        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/testimonies/form',[
            'title'     => 'Editar depoimento',
            'nome'      => $obTestimony->nome,
            'mensagem'  => $obTestimony->mensagem,
            'status'    => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Editar depoimento > Admin',$content, 'testimonies');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setEditTestimony($request,$id){
        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTÂNCIA
        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }

        //POST VARS
        $postVars = $request->getPostVars();

        //ATUALIZA A INSTÂNCIA
        $obTestimony->nome      = $postVars['nome'] ?? $obTestimony->nome;
        $obTestimony->mensagem  = $postVars['mensagem'] ?? $obTestimony->mensagem;
        $obTestimony->atualizar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=updated');
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um depoimento
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getDeleteTestimony($request,$id){
        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTÂNCIA
        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/testimonies/delete',[
            'nome'      => $obTestimony->nome,
            'mensagem'  => $obTestimony->mensagem
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Excluir depoimento > Admin',$content, 'testimonies');
    }

    /**
     * Método responsável excluir um depoimento
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setDeleteTestimony($request,$id){
        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTÂNCIA
        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }

        //EXCLUI O DEPOIMENTO
        $obTestimony->excluir();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/testimonies?status=deleted');
    }
}