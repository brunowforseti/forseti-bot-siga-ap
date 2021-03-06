<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 08:42
 */

namespace Forseti\Bot\Name\Parser;


use Symfony\Component\DomCrawler\Crawler;


abstract class AbstractParser
{
    protected $crawler;

    public function __construct($html)
    {
        $this->crawler = new Crawler();
        $this->crawler->addHtmlContent($html,'ISO-8859-1');
    }

    public function getHtml()
    {
        return $this->crawler->html();
    }
}