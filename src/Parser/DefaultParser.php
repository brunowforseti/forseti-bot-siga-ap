<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:00
 */

namespace Forseti\Bot\Name\Parser;

use Forseti\Bot\Name\Iterator\DefaultIterator;
use Forseti\Bot\Name\Iterator\AndamentoIterator;
use Forseti\Bot\Name\Iterator\FuturosIterator;
use Forseti\Bot\Name\Iterator\LotesIterator;
use Forseti\Bot\Name\Iterator\SessaoForLotesIterator;
use Forseti\Bot\Name\Iterator\EditaisIterator;


class DefaultParser extends AbstractParser
{
    public function getDefaultIterator($xpath)
    {
        return new DefaultIterator($this->getHtml(), $xpath);
    }

    public function getAndamentoIterator($xpath)
    {
        return new AndamentoIterator($this->getHtml(), $xpath);
    }

    public function getFuturosIterator($xpath)
    {
        return new FuturosIterator($this->getHtml(), $xpath);
    }

    public function getLotesIterator($xpath)
    {
        return new LotesIterator($this->getHtml(), $xpath);
    }

    public function getSessaoForLotesIterator($xpath)
    {
        return new SessaoForLotesIterator($this->getHtml(), $xpath);
    }

    public function getEditaisIterator($xpath)
    {
        return new EditaisIterator($this->getHtml(), $xpath);
    }

}