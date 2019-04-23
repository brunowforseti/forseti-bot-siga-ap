<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:30
 */

namespace Forseti\Bot\Name\Iterator;


class EditaisIterator extends AbstractIterator
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
        $idsEditais = $this->crawler->filterXPath('//a[contains(@onclick, "idEdital")]')->evaluate('substring-after(@onclick, "idEdital")');
        foreach ($idsEditais as $key => $preg) {
            $ids[] = preg_replace("/[^0-9]/", '' , $preg);
        }
        $data_abertura   = $node->getElementsByTagName('td')->item(0)->textContent;
        $numero           = $node->getElementsByTagName('td')->item(1)->textContent;
        $objeto          = $node->getElementsByTagName('td')->item(2)->textContent;
        $orgao          = $node->getElementsByTagName('span')->item(3)->textContent;
        $procedimento = $node->getElementsByTagName('td')->item(4)->textContent;
        $download              = $ids[$this->iterator->key()];
        $id              = $ids[$this->iterator->key()];

        $andamento = new \stdClass();
        $andamento->data_abertura = $data_abertura;
        $andamento->numero = $numero;
        $andamento->objeto = utf8_decode(trim($objeto));
        $andamento->procedimento = utf8_decode(trim($procedimento));
        $andamento->download = $download;
        $andamento->id = $id;
        return $andamento;
    }
}