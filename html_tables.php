<?php

require_once __DIR__ . "/vendor/autoload.php";

use Forseti\Bot\Name\PageObject\DefaultPageObject;
use Forseti\Bot\Name\Factory\GuzzleClientFactory;
use Symfony\Component\DomCrawler\Crawler;



// crawler id + pagina
$client = GuzzleClientFactory::getInstance();
$request = $client->request('GET', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPageList.jsp');
$html = $request->getBody()->getContents();
$crawler = new Crawler();
$crawler->addHtmlContent($html,'ISO-8859-1');
$vs = $crawler->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
$request = $client->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPageList.jsp', [
		'form_params' => [
			'formAssistirPageList'	=> 'formAssistirPageList',
			'formAssistirPageList:aboutModalHeadOpenedState' => '',
			'formAssistirPageList:orgaoCombo'	=>	'',
			'formAssistirPageList:modalidadeCombo' => '',
			'formAssistirPageList:processoInput' => '',
			'formAssistirPageList:objetoProcessoInput' => '',
			'formAssistirPageList:dtInicioTextInputDate' => '',
			'formAssistirPageList:dtInicioTextInputCurrentDate' => date('m/Y'),
			'formAssistirPageList:dtFimTextInputDate' => '',
			'formAssistirPageList:dtFimTextInputCurrentDate' > date('m/Y'),
			'formAssistirPageList:editalInput' => '',
			'formAssistirPageList:pesquisarButton' => 'Pesquisar',
			'javax.faces.ViewState' => $vs,
			'formAssistirPageList:agendaDataTable:1:visualizarLoteLink' => 'formAssistirPageList:agendaDataTable:1:visualizarLoteLink',
			'idPregao'	=> '10707'
		]
]);

$crawler2 = new Crawler();

$crawler2->addHtmlContent($request->getBody()->getContents(),'ISO-8859-1');
$vs2 = $crawler2->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');

var_dump($vs2); die;

// crawler id + pagina

// pega todos IDS

$att2 = $crawler->filterXPath('//a[contains(@onclick, "idPregao")]')->evaluate('substring-after(@onclick, "idPregao")');

foreach ($att2 as $key => $a) {
	$a = str_replace("'},'');}return false", '', $a);
	$a = str_replace("':'", '', $a);
	$idsPregao[] = $a;
}

echo "<pre>";
var_dump($idsPregao);
die();

$crawler->addHtmlContent($html,'ISO-8859-1');

// pega todos IDS

$html = $request->getBody()->getContents();

echo $html;






            if ($l->idLote->id <> '') {
                $lotes = $this->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/PregaoPageView.jsp', [
                            'form_params' => [
                                'form1' => 'form1',
                                'form1:aboutModalHeadOpenedState:' => '',
                                'form1:idPregao:' => $id,
                                'javax.faces.ViewState:' => $vs,
                                'form1:listaDataTable:1:detalheAtaLink:' => 'form1:listaDataTable:1:detalheAtaLink',
                                'idLote'    =>  $l->idLote->id,
                                'idPregao'  =>  $id
                            ]

                ]);

                $parserLote = new DefaultParser($lotes->getBody()->getContents());
                $linhasThru = $parserLote->getSessaoForLotesIterator('//table[@id="mensagensDataTable"]//tr[position() > 0]');
                
            }
