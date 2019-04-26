<?php

namespace Forseti\Bot\Name\PageObject;

use Forseti\Bot\Name\Parser\AndamentoParser;
use Forseti\Bot\Name\Enums\DefaultLink;
use Symfony\Component\DomCrawler\Crawler;

class PregoesAndamentoPageObject extends AbstractPageObject
{

    public function getAllAndamento()
    {
        $html = $this->getPage(DefaultLink::PREGAO_ASSISTIRPAGELIST, true);
        $andamentoParser = new AndamentoParser($html);
        $linhas = $andamentoParser->getAndamentoIterator('//table[@id="formAssistirPageList:agendaDataTable"]/tbody//tr[position() > 0]');
        foreach ($linhas as $key => $l) {
            $l->lotes = $this->getById($l->id);
            $l->data_abertura = $this->getDataAberturaForPregao($l->id);
            $andamento[] = $l;
        }
        if (isset($andamento)){
            $this->info('PO getAllAndamento OK');
            return $andamento;
        } else {
            $this->error('PO getAllAndamento Falha', $html);
            return '';
        }

    }

    public function search($numero_processo)
    {
        $request = $this->request('POST', DefaultLink::PREGAO_ASSISTIRPAGELIST, [
                'form_params' => [
                    'formAssistirPageList'  => 'formAssistirPageList',
                    'formAssistirPageList:aboutModalHeadOpenedState' => '',
                    'formAssistirPageList:orgaoCombo'   =>  0,
                    'formAssistirPageList:modalidadeCombo' => 0,
                    'formAssistirPageList:processoInput' => $numero_processo,
                    'formAssistirPageList:objetoProcessoInput' => '',
                    'formAssistirPageList:dtInicioTextInputDate' => '',
                    'formAssistirPageList:dtInicioTextInputCurrentDate' => date('m/Y'),
                    'formAssistirPageList:dtFimTextInputDate' => '',
                    'formAssistirPageList:dtFimTextInputCurrentDate' > date('m/Y'),
                    'formAssistirPageList:editalInput' => '',
                    'formAssistirPageList:pesquisarButton' => 'Pesquisar',
                    'javax.faces.ViewState' => $this->getViewState()
                ]
        ]);
        $andamentoParser = new AndamentoParser($request->getBody()->getContents());
        $linhas = $andamentoParser->getAndamentoIterator('//table[@id="formAssistirPageList:agendaDataTable"]/tbody//tr[position() > 0]');
        foreach ($linhas as $key => $l) {
            $l->lotes = $this->getById($l->id);
            $l->data_abertura = $this->getDataAberturaForPregao($l->id);
            $andamento[] = $l;
        }
        if (isset($andamento)){
            $this->info('search OK - Numero Processo:'.$numero_processo);
            return $andamento;
        } else {
            $this->erro('Falha no método Search.', 'numero_processo = '.$numero_processo);
            return '';
        }

    }

    public function getDataAberturaForPregao($id)
    {
        $request = $this->request('POST', DefaultLink::PREGAO_ASSISTIRPAGELIST, [
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
                    'javax.faces.ViewState' => $this->getViewState(),
                    'formAssistirPageList:agendaDataTable:1:visualizarLoteLink' => 'formAssistirPageList:agendaDataTable:1:visualizarLoteLink',
                    'idPregao'  => $id
                ]
        ]);
        $andamentoParser = new AndamentoParser($request->getBody()->getContents());
        $dataAbertura = $andamentoParser->getDataAberturaForLotes()->current();

        if (isset($dataAbertura)){
            $this->info('getDataAberturaForPregao OK - ID - '.$id);
            return $dataAbertura;
        } else {
            $this->erro('Falha no método getDataAberturaForPregao. ID - '.$id);
        }
    
    }

    public function getById($id = false)
    {
        // PAGINAÇÃO //
        $requestPage = $this->request('POST', DefaultLink::PREGAO_ASSISTIRPAGELIST, [
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
                    'javax.faces.ViewState' => $this->getViewState(),
                    'formAssistirPageList:agendaDataTable:1:visualizarLoteLink' => 'formAssistirPageList:agendaDataTable:1:visualizarLoteLink',
                    'idPregao'  => $id
                ]
        ]);
        $andamentoParser = new AndamentoParser($requestPage->getBody()->getContents());
        $pageQtd = $andamentoParser->getPaginacaoForLotes()->current();
        // FIM PAGINACAO //

        // COMECO CODIGO COM A PAGINACAO [LOTES] //
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

            $parserById = new AndamentoParser($request->getBody()->getContents());
            $linhas = $parserById->getLotesIterator('//tbody[@id="form1:listaDataTable:tb"]//tr[position() > 0]');

            foreach ($linhas as $key => $l) {
                // informações do lote //
                $l->sessao = $this->getByLote($l->idLote, $id);
                // get link do download //
                $l->download = $this->getAtaDownload($l->idLote, $id);
                $l->idPregao = $id;
                $lotes[] = $l;
            }
        }

        // FIM CODIGO COM A PAGINACAO //
        if (isset($lotes)){
            $this->info('getById OK. ID - '.$id);
            return $lotes;
        } else {
            $this->error('falha no getById - idPregao '.$id);
            return ['erro' => 'Nao foi possivel devolver o lote.', 'idPregao' => $id];
        }
    }

    public function getByLote($idLote, $idPregao)
    {
        $html = $this->getPage('http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/MensagemLerPageList.jsp?pregao='.$idPregao.'&lotePregao='.$idLote, true);
        $andamentoParser = new AndamentoParser($html);
        $linhasThru = $andamentoParser->getSessaoForLotesIterator('//table[@id="mensagensDataTable"]//tr[position() > 0]');
        foreach ($linhasThru as $key => $l) {
            $mensagem[] = $l;
        }
        if (!isset($mensagem)){
            $this->error('getByLote ERRO. IdLote - '.$idLote.', idPregao - '.$idPregao);
            $mensagem = ['erro' =>   'Nao foi possivel receber a mensagem.', 'idLote'    => $idLote, 'idPregao'  => $idPregao];
        } else {
            $this->info('getByLote OK. IdLote - '.$idLote.' - idPregao - '.$idPregao);
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
            $this->info('getAtaDownload OK - Baixado arquivo para : '.$path);
            return $path;
        } else {
            $this->info('Arquivo já existe. Sem necessidade de substituição.');
            return $path;
        }
    }

}