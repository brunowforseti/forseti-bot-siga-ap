<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 08:41
 */

namespace Forseti\Bot\Name\PageObject;

use Forseti\Bot\Name\Factory\GuzzleClientFactory;
use Forseti\Bot\Name\Traits\ForsetiLoggerTrait;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;
use Forseti\Bot\Name\Parser\DefaultParser;

abstract class AbstractPageObject
{
    use ForsetiLoggerTrait;

    protected $client;

    public function __construct()
    {
        $this->client = GuzzleClientFactory::getInstance();
    }

    public function request($method, $uri, array $options = [])
    {

        try {
            return $this->client->request($method, $uri, $options);
        } catch (GuzzleException $e) {
            $this->error('Erro ao tentar requisicao', ['exception' => $e]);
            return null;
        } catch (\Exception $e) {
            $this->error('Erro ao tentar requisicao', ['exception' => $e]);
            return null;
        }
    }

    public function getViewState()
    {
        $request = $this->client->request('GET', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPageList.jsp');
        $html = $request->getBody()->getContents();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html,'ISO-8859-1');
        $vs = $crawler->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
        return $vs;
    }

    public function getAndamento()
    {
        $po = new DefaultPageObject();
        $vs = $this->getViewState();
        $parserAndamento = $po->getPage('http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPageList.jsp');
        $linhas = $parserAndamento->getAndamentoIterator('//table[@id="formAssistirPageList:agendaDataTable"]/tbody//tr[position() > 0]');
        foreach ($linhas as $key => $l) {
            $idsComps[] = $l;
        }
        return $idsComps;
    }

    public function getFuturos()
    {
        $po = new DefaultPageObject();
        $vs = $this->getViewState();
        $parserFuturos = $po->getPage('http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/FuturosPageList.jsp');
        $linhas = $parserFuturos->getFuturosIterator('//table[@id="formFuturosPageList:agendaDataTable"]/tbody//tr[position() > 0]');

        foreach ($linhas as $key => $l) {
            $idsComps[] = $l;
        }

        return $idsComps;
    }

    public function getById($id = false)
    {
        if (!$id){
            die("Insira um ID Válido.");
        }
        $po = new DefaultPageObject();
        $vs = $this->getViewState();
        $request = $this->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPageList.jsp', [
                'form_params' => [
                    'formAssistirPageList'  => 'formAssistirPageList',
                    'formAssistirPageList:aboutModalHeadOpenedState' => '',
                    'formAssistirPageList:orgaoCombo'   =>  '',
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
                    'idPregao'  => $id
                ]
        ]);

        $parserById = new DefaultParser($request->getBody()->getContents());
        $linhas = $parserById->getLotesIterator('//tbody[@id="form1:listaDataTable:tb"]//tr[position() > 0]');

        foreach ($linhas as $key => $l) {
            $loteInfo = $this->getByLote($l->idLote->id, $id);
            $downloadLink = $this->getAtaDownload($l->idLote->id, $id);
            $l->download = $downloadLink;
            $l->idLote->sessao = $loteInfo;
            $l->idPregao = $id;
            $lotes[] = $l;
        }
        return $lotes;
    }

    public function getByLote($idLote, $idPregao)
    {
        $po = new DefaultPageObject();
        $link = 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/MensagemLerPageList.jsp?pregao='.$idPregao.'&lotePregao='.$idLote;
        $getMensagem = $po->getPage($link);
        $linhasThru = $getMensagem->getSessaoForLotesIterator('//table[@id="mensagensDataTable"]//tr[position() > 0]');
        foreach ($linhasThru as $key => $l) {
            $mensagem[] = $l;
        }
        return $mensagem;
    }

    public function getAtaDownload($idLote, $idPregao)
    {
        $request = $this->client->request('GET', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPregaoPageView.jsp');
        $html = $request->getBody()->getContents();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html,'ISO-8859-1');
        $vs = $crawler->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
        $path = __DIR__.'/download/'.$idPregao.'-'.$idLote.'-ATA-'.date('d-m-Y-H-i-s').'.pdf';
        $options = 
        [
                    'form_params' => [
                        'form1' => 'form1',
                        'form1:aboutModalHeadOpenedState' => '',
                        'form1:idLote'    => $idLote,
                        'form1:idPregao' => $idPregao,
                        'form1:tempo'    => '0',
                        'form1:imprimir1Button' => 'Imprimir Ata',
                        'javax.faces.ViewState' => $vs
                    ],
                    'save_to'   => fopen($path,'w'),
                    'debug' =>  true
        ];
        $file_get = $this->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPregaoPageView.jsp', $options);

        return $path;
    }


}