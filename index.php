<?php
require __DIR__ . '/vendor/autoload.php';
// Paginas //
use Forseti\Bot\Name\Enums\DefaultLink;
use Forseti\Bot\Name\PageObject\EditalPageObject;
use Forseti\Bot\Name\PageObject\PregoesAndamentoPageObject;
use Forseti\Bot\Name\PageObject\PregoesFuturosPageObject;

// Paginas //

// Testes //
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
	highlight_string("<?php\n " . var_export($editalPo->getAllEditais(), true) . "?>");
	echo '<script>document.getElementsByTagName("code")[0].getElementsByTagName("span")[1].remove();
	document.getElementsByTagName("code")[0].getElementsByTagName("span")[document.getElementsByTagName("code")[0].getElementsByTagName("span").length - 1].remove() ; </script>';
die();
/*var_dump($andamentoPo->getAllAndamento());*/
// var_dump($futurosPo->getAllFuturos());
/*var_dump($andamentoPo->getDataAberturaForPregao('10647'));*/
/*var_dump($andamentoPo->getById('10709'));*/
die();

// Paginas // 

// Testes //

// $editalDownloadTest = new EditalDownloadPageObjectTest();
// $editalPageListTest = new EditalPageListPageObjectTest();
// $futurosPageViewTest = new FuturosPageViewPageObjectTest();
// $pregaoAssistirPageListTest = new PregaoAssistirPageListPageObjectTest();
// $pregaoAssistirPageViewTest = new PregaoAssistirPregaoPageViewPageObjectTest();
// $pregaoPageViewTest = new PregaoPageViewPageObjectTest();

echo "<pre>";
// var_dump($editalDownloadTest->testIfResponseIsOK());
// var_dump($editalPageListTest->testIfResponseIsOK());
// var_dump($futurosPageViewTest->testIfResponseIsOK());
// var_dump($pregaoAssistirPageListTest->testIfResponseIsOK());
// var_dump($pregaoAssistirPageViewTest->testIfResponseIsOK());
// var_dump($pregaoPageViewTest->testIfResponseIsOK());
die();

// Testes //
