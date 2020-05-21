<?php

require 'vendor/autoload.php';

use Gabsdsousa\TrainingLooker\Looker;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client(['verify' => false, 'base_uri' => 'https://www.alura.com.br/']);
$crawler = new Crawler();
$looker = new Looker($client, $crawler);
$trainings = $looker->look('/cursos-online-programacao/php');

foreach ($trainings as $training) {
    echo $training . PHP_EOL;
}
