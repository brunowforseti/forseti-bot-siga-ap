<?php

namespace Forseti\Bot\Name\Iterator;


class DespesasIterator extends AbstractIterator
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

        $codigo             = $node->getElementsByTagName('td')->item(0)->textContent;
        $descricao             = $node->getElementsByTagName('td')->item(1)->textContent;

        $despesas = new \stdClass();
        $despesas->codigo              = $codigo;
        $despesas->descricao           = utf8_decode(trim($descricao));
        return $despesas;
    }
}