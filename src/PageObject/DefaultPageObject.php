<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 08:40
 */

namespace Forseti\Bot\Name\PageObject;

use Forseti\Bot\Name\Parser\DefaultParser;
use Forseti\Bot\Name\Enums\DefaultLink;

class DefaultPageObject extends AbstractPageObject
{
    public function getPage($link)
    {
        $response = $this->client->get($link);
        return new DefaultParser($response->getBody()->getContents());
    }

}