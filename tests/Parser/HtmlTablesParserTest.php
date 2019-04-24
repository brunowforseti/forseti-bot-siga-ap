<?php

namespace Forseti\Bot\Name\Test\Parser;

use Forseti\Bot\Name\Parser\DefaultParser;

class HtmlTablesParserTest extends \PHPUnit_Framework_TestCase
{
    private $parser;

    protected function setUp()
    {
        $html = file_get_contents(__DIR__ . '/resources/htmltables.html');
        $this->parser = new DefaultParser($html);
    }

    public function testRetornoHtml()
    {
        $this->assertNotEmpty($this->parser->getHtml());
    }

    public function testQuantidadeDeOportunidadesAchadas()
    {
        $quantidade = $this->parser->getDefaultIterator()->count();

        $this->assertEquals(6, $quantidade, 'Quantidade divergente');
    }

}