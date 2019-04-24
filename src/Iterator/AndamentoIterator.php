<?php

namespace Forseti\Bot\Name\Iterator;


class AndamentoIterator extends AbstractIterator
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
        $idsPregoes = $this->crawler->filterXPath('//a[contains(@onclick, "idPregao")]')->evaluate('substring-after(@onclick, "idPregao")');
        foreach ($idsPregoes as $key => $preg) {
            $ids[] = preg_replace("/[^0-9]/", '' , $preg);
        }
        $numero_edital   = $node->getElementsByTagName('td')->item(0)->textContent;
        $orgao           = $node->getElementsByTagName('td')->item(1)->textContent;
        $objeto          = $node->getElementsByTagName('td')->item(2)->textContent;
        $procedimento    = $node->getElementsByTagName('span')->item(3)->textContent;
        $numero_processo = $node->getElementsByTagName('td')->item(4)->textContent;
        $sala_horario    = $node->getElementsByTagName('td')->item(5)->textContent;
        $pregoeiro       = $node->getElementsByTagName('td')->item(6)->textContent;
        $id              = $ids[$this->iterator->key()];

        $andamento = new \stdClass();
        $andamento->numero_edital = $numero_edital;
        $andamento->orgao = $orgao;
        $andamento->procedimento = utf8_decode(trim($procedimento));
        $andamento->numero_processo = $numero_processo;
        $andamento->sala_horario = utf8_decode(trim($sala_horario));
        $andamento->pregoeiro = $pregoeiro;
        $andamento->id = $id;
        return $andamento;
    }
}