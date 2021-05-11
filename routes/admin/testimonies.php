<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA LISTAGEM DE DEPOIMENTOS
$obRouter->get('/admin/testimonies',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,Admin\Testimony::getTestimonies($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO DEPOIMENTO
$obRouter->get('/admin/testimonies/new',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,Admin\Testimony::getNewTestimony($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO DEPOIMENTO (POST)
$obRouter->post('/admin/testimonies/new',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,Admin\Testimony::setNewTestimony($request));
    }
]);
