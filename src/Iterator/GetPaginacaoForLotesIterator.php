<?php

namespace Forseti\Bot\Name\Iterator;


class GetPaginacaoForLotesIterator extends AbstractIterator
{
    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        $node = $this->iterator->current();
        $idCount = $this->crawler->filterXpath('(//table[@id="form1:listaDataTable:j_id_jsp_55323938_45_table"]//tr//td)')->count()-1;
        if ($idCount == 1){
            $idCount++;
        }
        return $idCount;
    }
}