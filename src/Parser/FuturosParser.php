<?php

namespace Forseti\Bot\Name\Parser;

use Forseti\Bot\Name\Iterator\FuturosIterator;

class FuturosParser extends AbstractParser
{
    public function getFuturosIterator($xpath)
    {
        return new FuturosIterator($this->getHtml(), $xpath);
    }

}