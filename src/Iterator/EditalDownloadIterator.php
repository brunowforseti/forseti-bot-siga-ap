<?php

namespace Forseti\Bot\Name\Iterator;


class EditalDownloadIterator extends AbstractIterator
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
        $sessao = str_replace(' ', '-', utf8_decode($this->crawler->filterXPath('(//table[contains(@id, "form1:j_id_jsp_")]//td[position() = 2])')->text()));
        return $sessao;
    }
}