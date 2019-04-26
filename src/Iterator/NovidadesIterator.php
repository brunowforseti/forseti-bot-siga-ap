<?php

namespace Forseti\Bot\Name\Iterator;


class NovidadesIterator extends AbstractIterator
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
        $news = utf8_decode($this->crawler->filterXpath('//span[@id="form1:novidadesText"]')->text());
        return $news;
    }
}