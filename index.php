<?php
require __DIR__ . '/vendor/autoload.php';

use Forseti\Bot\Name\PageObject\DefaultPageObject;

$page = new DefaultPageObject();

// consultas pregoes
/*$andamento = $page->getAndamento();*/
/*$futuros = $page->getFuturos();*/
/*$byId = $page->getById('10627'); */// faz a consulta pelo ID. 10728 tem..

// fim consulta pregoes

// editais licitacao
$getEditais = $page->getAllEditais();
echo "<pre>";
var_dump($getEditais);
die('q');
/*$getEditalDownload = $page->getEditalDownload('10746');*/
/*echo "<pre>";
var_dump($getEditais);
die();*/
// fim editais licitacao