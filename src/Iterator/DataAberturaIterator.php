<?php

namespace Forseti\Bot\Name\Iterator;


class DataAberturaIterator extends AbstractIterator
{
    public function current()
    {
        $node = $this->iterator->current();
        $dataAbertura = $this->crawler->filterXpath('(//span[@id="form1:dataAberturaText"])')->text();
        return $dataAbertura;
    }
}