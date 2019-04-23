<?php
require __DIR__ . '/vendor/autoload.php';

use Forseti\Bot\Name\PageObject\DefaultPageObject;
use Forseti\Bot\Name\PageObject\EditalPageObject;
use Forseti\Bot\Name\PageObject\PregoesAndamentoPageObject;
use Forseti\Bot\Name\PageObject\PregoesFuturosPageObject;

$editalPo = new EditalPageObject();
$andamentoPo = new PregoesAndamentoPageObject();
$futurosPo = new PregoesFuturosPageObject();

echo "<pre>";
/*var_dump($editalPo->getAllEditais());*/
/*var_dump($andamentoPo->getAllAndamento());*/
/*var_dump($futurosPo->getAllFuturos());*/
die();