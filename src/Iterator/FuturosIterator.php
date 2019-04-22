<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:30
 */

namespace Forseti\Bot\Name\Iterator;


class FuturosIterator extends AbstractIterator
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

        $numero_edital      = $node->getElementsByTagName('td')->item(0)->textContent;
        $numero_processo    = $node->getElementsByTagName('td')->item(1)->textContent;
        $numero_repeticao   = $node->getElementsByTagName('td')->item(2)->textContent;
        $data_abertura      = $node->getElementsByTagName('td')->item(3)->textContent;
        $objeto             = $node->getElementsByTagName('td')->item(4)->textContent;
        $orgao              = $node->getElementsByTagName('td')->item(5)->textContent;
        $procedimento       = $node->getElementsByTagName('td')->item(6)->textContent;
        $sala               = $node->getElementsByTagName('td')->item(7)->textContent;

        $futuros = new \stdClass();
        $futuros->numero_edital = $numero_edital;
        $futuros->numero_processo = $numero_processo;
        $futuros->numero_repeticao = utf8_decode(trim($numero_repeticao));
        $futuros->data_abertura = $data_abertura;
        $futuros->objeto = utf8_decode(trim($objeto));
        $futuros->orgao = $orgao;
        $futuros->procedimento = utf8_decode($procedimento);
        $futuros->sala = utf8_decode($sala);
        return $futuros;
    }
}