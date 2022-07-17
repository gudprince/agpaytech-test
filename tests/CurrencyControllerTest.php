<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class CurrencyControllerTest extends TestCase
{


    public function testSave()
    {
        $uploadedFile = Psr7\Utils::tryFopen(__DIR__ . '/../public/file/01-Currencies.csv', 'r');

        $client = new Client(['base_uri' => '127.0.0.1:8000/api/']);
        $response = $client->request('POST', 'currencies', [
            'multipart' => [
                [
                    'name' => 'currency',
                    'contents' => $uploadedFile,
                ],
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $result = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('data', $result);
    }

    public function testIndex()
    {

        $client = new Client(['base_uri' => '127.0.0.1:8000/api/']);
        $response = $client->request('GET', 'currencies');

        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $result = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('message', $result);
    }
}
