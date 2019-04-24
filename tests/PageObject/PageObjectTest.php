<?php

namespace Forseti\Bot\Name\Test\PageObject;

use Forseti\Bot\Name\Factory\GuzzleClientFactory;

abstract class PageObjectTest extends \PHPUnit_Framework_TestCase
{

    public function __construct()
    {
        $this->client = GuzzleClientFactory::getInstance();
    }

}