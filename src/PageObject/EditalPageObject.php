<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 08:40
 */

namespace Forseti\Bot\Name\PageObject;

use Forseti\Bot\Name\Parser\DefaultParser;
use Forseti\Bot\Name\Parser\EditalParser;
use Forseti\Bot\Name\Enums\DefaultLink;
use Symfony\Component\DomCrawler\Crawler;

class EditalPageObject extends AbstractPageObject
{

    public function getAllEditais()
    {
        $parser = $this->getPage(DefaultLink::EDITAL_PAGELIST);
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
        $options2Page = [
	                    'form_params' => [
	                        'form_EditalPageList' => 'form_EditalPageList',
	                        'form_EditalPageList:aboutModalHeadOpenedState' => '',
	                        'form_EditalPageList:editaisBidHidden'    => false,
	                        'form_EditalPageList:procurarPorCombo' => 3,
	                        'form_EditalPageList:situacaoCombo'    => 1,
	                        'javax.faces.ViewState' => $this->getViewState(DefaultLink::EDITAL_PAGELIST),
	                        'form_EditalPageList:listaDataTable:0:downloadLink' => 'form_EditalPageList:listaDataTable:0:downloadLink',
	                        'idEdital' => $idEdital,
	                    ],
        ];
        $receiveParam = $this->request('POST', DefaultLink::EDITAL_PAGELIST, $options2Page);
        
        $options = [
                    'form_params' => [
                        'form1' => 'form1',
                        'form1:aboutModalHeadOpenedState' => '',
                        'form1:razaoSocialInput'    => 'Teste',
                        'form1:emailInput' => 'teste@yopmail.com',
                        'form1:cpfCnpjInput'    => '48462945011',
                        'form1:telefoneInput' => '(11) 98989-8989',
                        'form1:salvar2Button' => 'Salvar',
                        'javax.faces.ViewState' => $this->getViewStateByRequest($receiveParam)
                    ],
        ];
        $file_get = $this->request('POST', DefaultLink::EDITAL_DOWNLOAD, $options);
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
        $options2Page = [
	                    'form_params' => [
	                        'form_EditalPageList' => 'form_EditalPageList',
	                        'form_EditalPageList:aboutModalHeadOpenedState' => '',
	                        'form_EditalPageList:editaisBidHidden'    => false,
	                        'form_EditalPageList:procurarPorCombo' => 3,
	                        'form_EditalPageList:situacaoCombo'    => 1,
	                        'javax.faces.ViewState' => $this->getViewState(DefaultLink::EDITAL_PAGELIST),
	                        'form_EditalPageList:listaDataTable:0:downloadLink' => 'form_EditalPageList:listaDataTable:0:downloadLink',
	                        'idEdital' => $idEdital,
	                    ],
        ];
        $acessoEdital = $this->request('POST', DefaultLink::EDITAL_PAGELIST, $options2Page);
        $options = [
                    'form_params' => [
                        'form1' => 'form1',
                        'form1:aboutModalHeadOpenedState' => '',
                        'form1:razaoSocialInput'    => 'Teste',
                        'form1:emailInput' => 'teste@yopmail.com',
                        'form1:cpfCnpjInput'    => '48462945011',
                        'form1:telefoneInput' => '(11) 98989-8989',
                        'form1:salvar2Button' => 'Salvar',
                        'javax.faces.ViewState' => $this->getViewStateByRequest($acessoEdital)
                    ],
        ];
        $file_get = $this->request('POST', DefaultLink::EDITAL_DOWNLOAD, $options);
        $parser = new EditalParser($file_get->getBody()->getContents());
        $nomeArquivo = $parser->getFileName()->current();
        $idAnexo = $parser->getIdAnexo()->current();
        $ViewState = $parser->getViewStateForDownload()->current();
        $path = __DIR__.'/download/'.$idEdital.'-'.$idAnexo.'-'.$nomeArquivo;
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
                            'javax.faces.ViewState' => $ViewState,
                            'form1:j_id_jsp_1063671723_51:0:downloadLink' => 'form1:j_id_jsp_1063671723_51:0:downloadLink',
                            'idAnexo' => $idAnexo,
                        ],
                        'save_to'   => fopen($path,'w')
            ];
            $this->request('POST', DefaultLink::EDITAL_DOWNLOAD, $options2Download);
            return $path;
        } else {
            return $path;
        }
    }

}