<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:00
 */

namespace Forseti\Bot\Name\Parser;

use Forseti\Bot\Name\Iterator\GetPaginacaoForLotesIterator;

class AndamentoParser extends AbstractParser
{
    public function getPaginacaoForLotes($xpath = '(//table[@id="form1:listaDataTable:j_id_jsp_55323938_45_table"]//tr//td)')
    {
        return new GetPaginacaoForLotesIterator($this->getHtml(), $xpath);
    }

}