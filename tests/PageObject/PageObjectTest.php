<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:58
 */

namespace Forseti\Bot\Name\Test\PageObject;

use Forseti\Bot\Name\Factory\GuzzleClientFactory;

abstract class PageObjectTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    protected $pageObject;

    protected function setUp()
    {
        $this->client = GuzzleClientFactory::getInstance();
    }

    abstract protected function testIfResponseIsOK();
    abstract protected function testRetornoHtml();

}