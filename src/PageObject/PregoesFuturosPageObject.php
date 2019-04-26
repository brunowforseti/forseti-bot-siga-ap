<?php

namespace Forseti\Bot\Name\PageObject;

use Forseti\Bot\Name\Parser\FuturosParser;
use Forseti\Bot\Name\Enums\DefaultLink;
use Symfony\Component\DomCrawler\Crawler;

class PregoesFuturosPageObject extends AbstractPageObject
{
    public function getAllFuturos()
    {
        $html = $this->getPage(DefaultLink::PREGAO_FUTUROPAGELIST, true);
        $parserFuturos = new FuturosParser($html);
        $linhas = $parserFuturos->getFuturosIterator('//table[@id="formFuturosPageList:agendaDataTable"]/tbody//tr[position() > 0]');
        foreach ($linhas as $key => $l) {
            $futuros[] = $l;
        }

        if (isset($futuros)){
            $this->info('PO getAllFuturos OK');
            return $futuros;
        } else {
            $this->error('Erro na requisição getAllFuturos', $html);
            return '';
        }

    }
}