<?php

namespace Forseti\Bot\Name\Parser;

use Forseti\Bot\Name\Iterator\AndamentoIterator;
use Forseti\Bot\Name\Iterator\LotesIterator;
use Forseti\Bot\Name\Iterator\SessaoForLotesIterator;
use Forseti\Bot\Name\Iterator\GetPaginacaoForLotesIterator;
use Forseti\Bot\Name\Iterator\DataAberturaIterator;

class AndamentoParser extends AbstractParser
{
    public function getAndamentoIterator($xpath)
    {
        return new AndamentoIterator($this->getHtml(), $xpath);
    }

    public function getLotesIterator($xpath)
    {
        return new LotesIterator($this->getHtml(), $xpath);
    }

    public function getSessaoForLotesIterator($xpath)
    {
        return new SessaoForLotesIterator($this->getHtml(), $xpath);
    }

    public function getPaginacaoForLotes($xpath = '(//table[@id="form1:listaDataTable:j_id_jsp_55323938_45_table"]//tr//td)')
    {
        return new GetPaginacaoForLotesIterator($this->getHtml(), $xpath);
    }
    
    public function getDataAberturaForLotes($xpath = '(//table[@class="TabPainelFundo"]//tbody//tr//td//span[@id="form1:dataAberturaText"])')
    {
        return new DataAberturaIterator($this->getHtml(), $xpath);
    }

}