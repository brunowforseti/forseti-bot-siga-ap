<?php
require __DIR__ . '/vendor/autoload.php';

use Forseti\Bot\Name\Enums\DefaultLink;
use Forseti\Bot\Name\PageObject\EditalPageObject;
use Forseti\Bot\Name\PageObject\PregoesAndamentoPageObject;
use Forseti\Bot\Name\PageObject\PregoesFuturosPageObject;
use Forseti\Bot\Name\Test\PageObject\EditalDownloadPageObjectTest;
use Forseti\Bot\Name\Test\PageObject\EditalPageListPageObjectTest;
use Forseti\Bot\Name\Test\PageObject\FuturosPageViewPageObjectTest;
use Forseti\Bot\Name\Test\PageObject\PregaoAssistirPageListPageObjectTest;
use Forseti\Bot\Name\Test\PageObject\PregaoAssistirPregaoPageViewPageObjectTest;
use Forseti\Bot\Name\Test\PageObject\PregaoPageViewPageObjectTest;


$editalPo = new EditalPageObject();
$andamentoPo = new PregoesAndamentoPageObject();
$futurosPo = new PregoesFuturosPageObject();


// Paginas //

echo "<pre>";
var_dump($editalPo->getAllEditais());
var_dump($andamentoPo->getAllAndamento());
var_dump($futurosPo->getAllFuturos());
// var_dump($andamentoPo->getById('10709'));
die();

// Paginas // 

// Testes //

$editalDownloadTest = new EditalDownloadPageObjectTest();
$editalPageListTest = new EditalPageListPageObjectTest();
$futurosPageViewTest = new FuturosPageViewPageObjectTest();
$pregaoAssistirPageListTest = new PregaoAssistirPageListPageObjectTest();
$pregaoAssistirPageViewTest = new PregaoAssistirPregaoPageViewPageObjectTest();
$pregaoPageViewTest = new PregaoPageViewPageObjectTest();

echo "<pre>";
// var_dump($editalDownloadTest->testIfResponseIsOK());
// var_dump($editalPageListTest->testIfResponseIsOK());
// var_dump($futurosPageViewTest->testIfResponseIsOK());
// var_dump($pregaoAssistirPageListTest->testIfResponseIsOK());
// var_dump($pregaoAssistirPageViewTest->testIfResponseIsOK());
// var_dump($pregaoPageViewTest->testIfResponseIsOK());
die();

// Testes //
