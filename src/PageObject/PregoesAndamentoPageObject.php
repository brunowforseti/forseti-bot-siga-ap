<?php

namespace Forseti\Bot\Name\PageObject;

use Forseti\Bot\Name\Parser\DefaultParser;
use Forseti\Bot\Name\Parser\AndamentoParser;
use Forseti\Bot\Name\Enums\DefaultLink;
use Symfony\Component\DomCrawler\Crawler;

class PregoesAndamentoPageObject extends AbstractPageObject
{

    public function getAllAndamento()
    {
        $parserAndamento = $this->getPage(DefaultLink::PREGAO_ASSISTIRPAGELIST);
        $linhas = $parserAndamento->getAndamentoIterator('//table[@id="formAssistirPageList:agendaDataTable"]/tbody//tr[position() > 0]');
        foreach ($linhas as $key => $l) {
            $l->loteInfo = $this->getById($l->id);
            $andamento[] = $l;
        }
        return $andamento;
    }

    public function getById($id = false)
    {
        // PAGINAÇÃO //
        $requestPage = $this->request('POST', DefaultLink::PREGAO_ANDAMENTOPREGAOPAGEVIEW, [
                'form_params' => [
                    'AJAXREQUEST'  => 'j_id_jsp_55323938_0',
                    'form1' => 'form1',
                    'form1:aboutModalHeadOpenedState'   =>  '',
                    'form1:idPregao' => $id,
                    'javax.faces.ViewState' => $this->getViewState(DefaultLink::PREGAO_ANDAMENTOPREGAOPAGEVIEW),
                    'ajaxSingle' => 'form1:listaDataTable:j_id_jsp_55323938_45',
                    'form1:listaDataTable:j_id_jsp_55323938_45' => 1,
                    'AJAX:EVENTS_COUNT' => '1'
                ]
        ]);
        $andamentoParser = new AndamentoParser($requestPage->getBody()->getContents());
        $pageQtd = $andamentoParser->getPaginacaoForLotes()->current();
        // FIM PAGINACAO //

        // COMECO CODIGO COM A PAGINACAO //
        for ($i=1; $i < $pageQtd ; $i++) { 
            $request = $this->request('POST', DefaultLink::PREGAO_ANDAMENTOPREGAOPAGEVIEW, [
                    'form_params' => [
                        'AJAXREQUEST'  => 'j_id_jsp_55323938_0',
                        'form1' => 'form1',
                        'form1:aboutModalHeadOpenedState'   =>  '',
                        'form1:idPregao' => $id,
                        'javax.faces.ViewState' => $this->getViewState(DefaultLink::PREGAO_ANDAMENTOPREGAOPAGEVIEW),
                        'ajaxSingle' => 'form1:listaDataTable:j_id_jsp_55323938_45',
                        'form1:listaDataTable:j_id_jsp_55323938_45' => $i,
                        'AJAX:EVENTS_COUNT' => '1'
                    ]
            ]);

            $parserById = new DefaultParser($request->getBody()->getContents());
            $linhas = $parserById->getLotesIterator('//tbody[@id="form1:listaDataTable:tb"]//tr[position() > 0]');

            foreach ($linhas as $key => $l) {
                $loteInfo = $this->getByLote($l->idLote->id, $id);
                $l->idLote->sessao = $loteInfo;
                $downloadLink = $this->getAtaDownload($l->idLote->id, $id);
                $l->download = $downloadLink;
                $l->idPregao = $id;
                $lotes[] = $l;
            }
        }

        // FIM CODIGO COM A PAGINACAO //
        if (isset($lotes)){
            return $lotes;
        } else {
            return ['erro' => 'Nao foi possivel devolver o lote.', 'idPregao' => $id];
        }
    }

    public function getByLote($idLote, $idPregao)
    {
        $getMensagem = $this->getPage('http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/MensagemLerPageList.jsp?pregao='.$idPregao.'&lotePregao='.$idLote);
        $linhasThru = $getMensagem->getSessaoForLotesIterator('//table[@id="mensagensDataTable"]//tr[position() > 0]');
        foreach ($linhasThru as $key => $l) {
            $mensagem[] = $l;
        }
        if (!isset($mensagem)){
            $mensagem = ['erro' =>   'Nao foi possivel receber a mensagem.', 'idLote'    => $idLote, 'idPregao'  => $idPregao];
        } else {
            return $mensagem;           
        }
    }

    public function getAtaDownload($idLote, $idPregao)
    {
        $path = __DIR__.'/download/'.$idPregao.'-'.$idLote.'-ATA.pdf';
        if (!file_exists($path)){
            $options = 
            [
                        'form_params' => [
                            'form1' => 'form1',
                            'form1:aboutModalHeadOpenedState' => '',
                            'form1:idLote'    => $idLote,
                            'form1:idPregao' => $idPregao,
                            'form1:tempo'    => '0',
                            'form1:imprimir1Button' => 'Imprimir Ata',
                            'javax.faces.ViewState' => $this->getViewState(DefaultLink::PREGAO_ANDAMENTOASSISTIRPREGAOPAGEVIEW)
                        ],
                        'save_to'   => fopen($path,'w')
            ];
            $file_get = $this->request('POST', DefaultLink::PREGAO_ANDAMENTOASSISTIRPREGAOPAGEVIEW, $options);
            return $path;
        } else {
            return $path;
        }
    }

}