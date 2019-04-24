<?php

namespace Forseti\Bot\Name\Test\PageObject;

use Forseti\Bot\Name\PageObject\DefaultPageObject;
use Forseti\Bot\Name\Enums\DefaultLink;

class FuturosPageViewPageObjectTest extends PageObjectTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->pageObject = new DefaultPageObject($this->client);
    }

    public function testIfResponseIsOK()
    {

        $response = $this->client->request('GET', DefaultLink::PREGAO_FUTUROPAGELIST);

        $this->assertEquals(200, $response->getStatusCode());

        return $response->getBody()->getContents();
    }
    /**
     * @depends testIfResponseIsOK
     */
    public function testRetornoHtml()
    {
        $html = func_get_args(); // o html e retornado da funcao acima e injetado atraves da notation @depends

        $this->assertNotEmpty($html[0], 'Nao retornou o html da pagina');
    }
}