<?php

namespace Forseti\Bot\Name\Parser;

use Forseti\Bot\Name\Iterator\DefaultIterator;

class DefaultParser extends AbstractParser
{
    // public function getDefaultIterator($xpath)
    // {
    //     return new DefaultIterator($this->getHtml(), $xpath);
    // }

    public function getDefaultIterator()
    {
        return new DefaultIterator($this->getHtml(), '(//table[@id="customers"]//tr)[position() > 1]');
    }

}