<?php

namespace Gabsdsousa\TrainingLooker;

use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class Looker
{
    /**
     * @var ClientInterface
     */
    private $httpClient;
    /**
     * @var Crawler
     */
    private $crawler;

    public function __construct(ClientInterface $httpClient, Crawler $crawler)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
    }

    public function look(string $url): array
    {

        $response = $this->httpClient->request('GET', $url);
        $html = $response->getBody();

        /** @var Symfony\Component\DomCrawler\string $html
         *  @var Symfony\Component\DomCrawler\string $selector
         */
        $this->crawler->addHtmlContent($html);
        $selector = 'span.card-curso__nome';
        $trainings = $this->crawler->filter($selector);
        $trainings_array = [];
        foreach ($trainings as $training) {
            $trainings_array[] = $training->textContent;
        }

        return $trainings_array;
    }
}
