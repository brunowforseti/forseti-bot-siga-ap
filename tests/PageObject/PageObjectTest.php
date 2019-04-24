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

    public function __construct()
    {
        $this->client = GuzzleClientFactory::getInstance();
    }

}