<?php

namespace Forseti\Bot\Name\Iterator;

use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractIterator implements ForsetiInterface
{

    protected $crawler;
    protected $iterator;

    public function __construct($html, $xpath, $charset = 'ISO-8859-1')
    {
        $this->crawler = new Crawler();
        $this->crawler->addHtmlContent($html, $charset);
        $this->iterator = $this->crawler->filterXPath($xpath)->getIterator();
    }

    public function next()
    {
        $this->iterator->next();
    }

    public function key()
    {
        return $this->iterator->key();
    }

    public function valid()
    {
        return $this->iterator->valid();
    }

    public function rewind()
    {
        $this->iterator->rewind();
    }

    public function count()
    {
        return $this->iterator->count();
    }

}