<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:30
 */

namespace Forseti\Bot\Name\Iterator;


class ViewStateDownloadIterator extends AbstractIterator
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
        $vs = $this->crawler->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
        return $vs;
    }
}