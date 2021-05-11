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