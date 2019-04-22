<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:30
 */

namespace Forseti\Bot\Name\Iterator;


class SessaoForLotesIterator extends AbstractIterator
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

        $data               = $node->getElementsByTagName('td')->item(0)->textContent;
        $postby             = $node->getElementsByTagName('td')->item(1)->textContent;
        $mensagem           = $node->getElementsByTagName('td')->item(2)->textContent;

        $sessao = new \stdClass();
        $sessao->data              = $data;
        $sessao->postby            = utf8_decode(trim($postby));
        $sessao->mensagem          = utf8_decode(trim($mensagem));
        return $sessao;
    }
}