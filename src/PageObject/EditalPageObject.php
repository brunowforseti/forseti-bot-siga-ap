<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 08:40
 */

namespace Forseti\Bot\Name\PageObject;

use Forseti\Bot\Name\Parser\DefaultParser;
use Forseti\Bot\Name\Enums\DefaultLink;

class EditalPageObject extends AbstractPageObject
{
    public function getPage($link)
    {
        $response = $this->client->get($link);
        return new DefaultParser($response->getBody()->getContents());
    }
    public function getAllEditais()
    {
        $po = new DefaultPageObject();
        $vs = $this->getViewState();
        $parser = $po->getPage('http://www.siga.ap.gov.br/sgc/faces/pub/sgc/central/EditalPageList.jsp');
        $linhas = $parser->getEditaisIterator('//table[@id="form_EditalPageList:listaDataTable"]/tbody//tr[position() > 0]');
        foreach ($linhas as $key => $l) {
            $l->itens_despesa = $this->getDespesasEdital($l->download);
            $l->download = $this->getEditalDownload($l->download);
            $idsEditais[] = $l;
        }
        return $idsEditais;
    }

    public function getDespesasEdital($idEdital) 
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