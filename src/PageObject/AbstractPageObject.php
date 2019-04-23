<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 08:41
 */

namespace Forseti\Bot\Name\PageObject;

use Forseti\Bot\Name\Factory\GuzzleClientFactory;
use Forseti\Bot\Name\Traits\ForsetiLoggerTrait;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;
use Forseti\Bot\Name\Parser\DefaultParser;

abstract class AbstractPageObject
{
    use ForsetiLoggerTrait;

    protected $client;

    public function getPage($link)
    {
        $response = $this->client->get($link);
        return new DefaultParser($response->getBody()->getContents());
    }

    public function __construct()
    {
        $this->client = GuzzleClientFactory::getInstance();
    }

    public function request($method, $uri, array $options = [])
    {

        try {
            return $this->client->request($method, $uri, $options);
        } catch (GuzzleException $e) {
            $this->error('Erro ao tentar requisicao', ['exception' => $e]);
            return null;
        } catch (\Exception $e) {
            $this->error('Erro ao tentar requisicao', ['exception' => $e]);
            return null;
        }
    }

    public function getViewState($url = false)
    {
        if (!$url)
            $url = 'http://www.siga.ap.gov.br/sgc/faces/pub/sgc/pregao/AssistirPageList.jsp';

        $request = $this->client->request('GET', $url);
        $html = $request->getBody()->getContents();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html,'ISO-8859-1');
        $vs = $crawler->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
        return $vs;
    }

    public function getViewStateByRequest($request)
    {
        $html = $request->getBody()->getContents();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html,'ISO-8859-1');
        $vs = $crawler->filterXpath('(//input[@id="javax.faces.ViewState"])')->attr('value');
        return $vs;
    }

}