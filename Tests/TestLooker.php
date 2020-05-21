<?php
namespace Gabsdsousa\Tests;

use Gabsdsousa\TrainingLooker\Looker;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\DomCrawler\Crawler;

class TestLooker extends TestCase
{
    private $httpClientMock;
    private $url = 'url-teste';

    protected function setUp(): void
    {
        $html = /** @lang text */
            <<<END
        <html>
            <body>
                <span class="card-curso__nome">Training Test 1</span>
                <span class="card-curso__nome">Training Test 2</span>
                <span class="card-curso__nome">Training Test 3</span>
            </body>
        </html>
        END;


        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects($this->once())
            ->method('__toString')
            ->willReturn($html);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $httpClient = $this
            ->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', $this->url)
            ->willReturn($response);

        $this->httpClientMock = $httpClient;
    }

    public function testLookerWithTrainingsReturn()
    {
        $crawler = new Crawler();
        $looker = new Looker($this->httpClientMock, $crawler);
        $trainnings = $looker->look($this->url);

        $this->assertCount(3, $trainnings);
        $this->assertEquals('Training Test 1', $trainnings[0]);
        $this->assertEquals('Training Test 2', $trainnings[1]);
        $this->assertEquals('Training Test 3', $trainnings[2]);
    }
}
