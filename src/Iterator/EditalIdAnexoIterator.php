<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:30
 */

namespace Forseti\Bot\Name\Iterator;


class EditalIdAnexoIterator extends AbstractIterator
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
        $id = preg_replace("/[^0-9]/", '' , $this->crawler->filterXPath('(//a[contains(@onclick, "idAnexo")][contains(@id, "downloadLink")])')->evaluate('substring-after(@onclick, "idAnexo\':\'")'));
        return $id[0];
    }
}