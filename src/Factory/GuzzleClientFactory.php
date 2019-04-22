<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 08:39
 */

namespace Forseti\Bot\Name\Factory;

use GuzzleHttp\Client;

final class GuzzleClientFactory
{
    public static function getInstance($debug = false)
    {
        $config = [
            'debug' => $debug,
            'cookies' => true,
            'verify' => false,
            //'referer' => 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPageList.jsp',
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36',
                //  'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'
            ]
        ];

        return new Client($config);
    }
}