<?php

require __DIR__.'/vendor/autoload.php';


use \App\Entity\Vaga;

//Busca

$busca = filter_input(INPUT_GET, 'busca', FILTER_SANITIZE_STRING);

// filtro de status

$filtroStatus = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);
$filtroStatus = in_array($filtroStatus,['s','n']) ? $filtroStatus : '';



//Condições SQL

$condicoes = [
    strlen($busca) ? 'titulo LIKE "%'.str_replace(' ','%',$busca).'%"' : null,
    strlen($filtroStatus) ? 'ativo = "'.$filtroStatus.'"' : null
];

//remove posições vazias
$condicoes = array_filter($condicoes);


// CLAUSULA WHERE
$where = implode(' AND ',$condicoes);

//Obtenha as vagas
$vagas = Vaga::getVagas($where);



include __DIR__.'/includes/header.php';
include __DIR__.'/includes/listagem.php';
include __DIR__.'/includes/footer.php';