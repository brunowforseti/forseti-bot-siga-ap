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
use Symfony\Component\DomCrawler\Crawler;

class PregoesFuturosPageObject extends AbstractPageObject
{
    public function getAllFuturos()
    {
        $parserFuturos = $this->getPage('http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/FuturosPageList.jsp');
        $linhas = $parserFuturos->getFuturosIterator('//table[@id="formFuturosPageList:agendaDataTable"]/tbody//tr[position() > 0]');
        foreach ($linhas as $key => $l) {
            $futuros[] = $l;
        }
        return $futuros;
    }
}