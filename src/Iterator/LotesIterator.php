<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:30
 */

namespace Forseti\Bot\Name\Iterator;


class LotesIterator extends AbstractIterator
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
        $idsLotes = $this->crawler->filterXPath('//a[contains(@onclick, "idLote")][contains(@id, "detalheAtaLink")]')->evaluate('substring-after(@onclick, "idLote\':\'")');

        foreach ($idsLotes as $key => $preg) {
            $ids[] = preg_replace("/(.*?)'(.*)/", "$1", $preg);
        }

        $lote               = $node->getElementsByTagName('td')->item(0)->textContent;
        $fase               = $node->getElementsByTagName('td')->item(1)->textContent;
        $situacao           = $node->getElementsByTagName('td')->item(2)->textContent;
        $resultado          = $node->getElementsByTagName('td')->item(3)->textContent;
        $idLote             = $ids[$this->iterator->key()];
        $download           = '#';

        $lotes = new \stdClass();
        $lotes->idLote = new \stdClass();

        $lotes->lote            = $lote;
        $lotes->fase            = utf8_decode(trim($fase));
        $lotes->situacao        = utf8_decode(trim($situacao));
        $lotes->resultado       = utf8_decode(trim($resultado));
        $lotes->idLote->id      = $idLote;
        $lotes->download        = $download;
        return $lotes;
    }
}