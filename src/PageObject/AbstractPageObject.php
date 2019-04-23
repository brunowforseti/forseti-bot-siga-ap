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

    public function getViewState($url = false)
    {
        if (!$url)
            $url = 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPageList.jsp';

        $request = $this->client->request('GET', $url);
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
            /*$l->loteInfo = $this->getById($l->id);*/
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
        $vs = $this->getViewState('http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/PregaoPageView.jsp');


        // PAGINAÇÃO //
        $requestPage = $this->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/PregaoPageView.jsp', [
                'form_params' => [
                    'AJAXREQUEST'  => 'j_id_jsp_55323938_0',
                    'form1' => 'form1',
                    'form1:aboutModalHeadOpenedState'   =>  '',
                    'form1:idPregao' => $id,
                    'javax.faces.ViewState' => $vs,
                    'ajaxSingle' => 'form1:listaDataTable:j_id_jsp_55323938_45',
                    'form1:listaDataTable:j_id_jsp_55323938_45' => 1,
                    'AJAX:EVENTS_COUNT' => '1'
                ]
        ]);
        $crawler = new Crawler();
        $crawler->addHtmlContent($requestPage->getBody()->getContents(),'ISO-8859-1');
        $pageQtd = $crawler->filterXpath('(//table[@id="form1:listaDataTable:j_id_jsp_55323938_45_table"]//tr//td)')->count()-1;
        // FIM PAGINACAO //

        // COMECO CODIGO COM A PAGINACAO //
        for ($i=1; $i < $pageQtd ; $i++) { 
            $request = $this->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/PregaoPageView.jsp', [
                    'form_params' => [
                        'AJAXREQUEST'  => 'j_id_jsp_55323938_0',
                        'form1' => 'form1',
                        'form1:aboutModalHeadOpenedState'   =>  '',
                        'form1:idPregao' => $id,
                        'javax.faces.ViewState' => $vs,
                        'ajaxSingle' => 'form1:listaDataTable:j_id_jsp_55323938_45',
                        'form1:listaDataTable:j_id_jsp_55323938_45' => $i,
                        'AJAX:EVENTS_COUNT' => '1'
                    ]
            ]);

            $parserById = new DefaultParser($request->getBody()->getContents());
            $linhas = $parserById->getLotesIterator('//tbody[@id="form1:listaDataTable:tb"]//tr[position() > 0]');

            foreach ($linhas as $key => $l) {
    /*            $loteInfo = $this->getByLote($l->idLote->id, $id);
                $downloadLink = $this->getAtaDownload($l->idLote->id, $id);
                $l->download = $downloadLink;
                $l->idLote->sessao = $loteInfo;*/
                $l->idPregao = $id;
                $lotes[] = $l;
            }
        }
        // FIM CODIGO COM A PAGINACAO //
        return $lotes;
    }

    public function getByItensDeDespesa($idEdital) 
    {
        $request = $this->client->request('GET', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/central/EditalPageList.jsp');
        $html = $request->getBody()->getContents();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html,'ISO-8859-1');
        $vs = $crawler->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
        $options2Page = 
        [
                    'form_params' => [
                        'form_EditalPageList' => 'form_EditalPageList',
                        'form_EditalPageList:aboutModalHeadOpenedState' => '',
                        'form_EditalPageList:editaisBidHidden'    => false,
                        'form_EditalPageList:procurarPorCombo' => 3,
                        'form_EditalPageList:situacaoCombo'    => 1,
                        'javax.faces.ViewState' => $vs,
                        'form_EditalPageList:listaDataTable:0:downloadLink' => 'form_EditalPageList:listaDataTable:0:downloadLink',
                        'idEdital' => $idEdital,
                    ],
        ];
        $receiveParam = $this->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/central/EditalPageList.jsp', $options2Page);
        $html2 = $receiveParam->getBody()->getContents();
        $crawler2 = new Crawler();
        $crawler2->addHtmlContent($html2,'ISO-8859-1');
        $vs2 = $crawler2->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
        $options = 
        [
                    'form_params' => [
                        'form1' => 'form1',
                        'form1:aboutModalHeadOpenedState' => '',
                        'form1:razaoSocialInput'    => 'Teste',
                        'form1:emailInput' => 'teste@yopmail.com',
                        'form1:cpfCnpjInput'    => '48462945011',
                        'form1:telefoneInput' => '(11) 98989-8989',
                        'form1:salvar2Button' => 'Salvar',
                        'javax.faces.ViewState' => $vs2
                    ],
        ];
        $file_get = $this->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/central/DownloadEditalPageList.jsp', $options);
        $parserById = new DefaultParser($file_get->getBody()->getContents());
        $linhasItens = $parserById->getDespesasIterator('//table[@id="form1:objetoProtegidoDataTable"]//tbody//tr[position() > 0]');

        foreach ($linhasItens as $key => $l) {
            $itens_despesa[] = $l;
        }

        if (isset($itens_despesa))
            return $itens_despesa;
        else
            return '';
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
        $path = __DIR__.'/download/'.$idPregao.'-'.$idLote.'-ATA.pdf';
        if (!file_exists($path)){
            $request = $this->client->request('GET', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPregaoPageView.jsp');
            $html = $request->getBody()->getContents();
            $crawler = new Crawler();
            $crawler->addHtmlContent($html,'ISO-8859-1');
            $vs = $crawler->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
            // $path = __DIR__.'/download/'.$idPregao.'-'.$idLote.'-ATA-'.date('d-m-Y-H-i-s').'.pdf';
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
                        'save_to'   => fopen($path,'w')
            ];
            $file_get = $this->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPregaoPageView.jsp', $options);
            return $path;
        } else {
            return $path;
        }
    }

    public function getAllEditais()
    {
        $po = new DefaultPageObject();
        $vs = $this->getViewState();
        $parser = $po->getPage('http://www.siga.ap.gov.br/sgc/faces/pub/sgc/central/EditalPageList.jsp');
        $linhas = $parser->getEditaisIterator('//table[@id="form_EditalPageList:listaDataTable"]/tbody//tr[position() > 0]');
        foreach ($linhas as $key => $l) {
            $l->itens_despesa = $this->getByItensDeDespesa($l->download);
            $l->download = $this->getEditalDownload($l->download);
            $idsEditais[] = $l;
        }
        return $idsEditais;
    }

    public function getEditalDownload($idEdital)
    {
        $request = $this->client->request('GET', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/central/EditalPageList.jsp');
        $html = $request->getBody()->getContents();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html,'ISO-8859-1');
        $vs = $crawler->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
        // $path = __DIR__.'/download/'.$idPregao.'-'.$idLote.'-ATA-'.date('d-m-Y-H-i-s').'.pdf';
        $options2Page = 
        [
                    'form_params' => [
                        'form_EditalPageList' => 'form_EditalPageList',
                        'form_EditalPageList:aboutModalHeadOpenedState' => '',
                        'form_EditalPageList:editaisBidHidden'    => false,
                        'form_EditalPageList:procurarPorCombo' => 3,
                        'form_EditalPageList:situacaoCombo'    => 1,
                        'javax.faces.ViewState' => $vs,
                        'form_EditalPageList:listaDataTable:0:downloadLink' => 'form_EditalPageList:listaDataTable:0:downloadLink',
                        'idEdital' => $idEdital,
                    ],
        ];
        $receiveParam = $this->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/central/EditalPageList.jsp', $options2Page);
        $html2 = $receiveParam->getBody()->getContents();
        $crawler2 = new Crawler();
        $crawler2->addHtmlContent($html2,'ISO-8859-1');
        $vs2 = $crawler2->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
        $options = 
        [
                    'form_params' => [
                        'form1' => 'form1',
                        'form1:aboutModalHeadOpenedState' => '',
                        'form1:razaoSocialInput'    => 'Teste',
                        'form1:emailInput' => 'teste@yopmail.com',
                        'form1:cpfCnpjInput'    => '48462945011',
                        'form1:telefoneInput' => '(11) 98989-8989',
                        'form1:salvar2Button' => 'Salvar',
                        'javax.faces.ViewState' => $vs2
                    ],
        ];
        $file_get = $this->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/central/DownloadEditalPageList.jsp', $options);
        $html3 = $file_get->getBody()->getContents();
        $crawler3 = new Crawler();
        $crawler3->addHtmlContent($html3,'ISO-8859-1');
        $vs3 = $crawler3->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
        $form = $crawler3->filterXpath('(//table[contains(@id, "form1:j_id_jsp_")])')->attr('id');
        $nomeArquivo = $crawler3->filterXpath('(//table[contains(@id, "form1:j_id_jsp_")]//td[position() = 2])')->text();
        $idAnexo = preg_replace("/[^0-9]/", '' , $crawler3->filterXPath('//a[contains(@onclick, "idAnexo")][contains(@id, "downloadLink")]')->evaluate('substring-after(@onclick, "idAnexo\':\'")'));
        $path = __DIR__.'/download/'.$idEdital.'-'.$idAnexo[0].'-'.$nomeArquivo;
        if (!file_exists($path)){
            $options2Download = 
            [
                        'form_params' => [
                            'form1' => 'form1',
                            'form1:aboutModalHeadOpenedState' => '',
                            'form1:razaoSocialInput'    => 'Teste',
                            'form1:emailInput' => 'teste@yopmail.com',
                            'form1:cpfCnpjInput'    => '48462945011',
                            'form1:telefoneInput' => '(11) 98989-8989',
                            'javax.faces.ViewState' => $vs3,
                            'form1:j_id_jsp_1063671723_51:0:downloadLink' => 'form1:j_id_jsp_1063671723_51:0:downloadLink',
                            'idAnexo' => $idAnexo[0],
                        ],
                        'save_to'   => fopen($path,'w')
            ];
            $file_get2 = $this->request('POST', 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/central/DownloadEditalPageList.jsp', $options2Download);
            return $path;
        } else {
            return $path;
        }
    }

}