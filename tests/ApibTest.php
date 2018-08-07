<?php

namespace Tests;

use Goez\ApibUnit\Apib;
use Goez\ApibUnit\Apib\Example;
use Goez\ApibUnit\Apib\Request;
use Goez\ApibUnit\Apib\Response;
use Goez\ApibUnit\Endpoint;
use PHPUnit\Framework\TestCase;

class ApibTest extends TestCase
{
    /**
     * @var \Goez\ApibUnit\Apib
     */
    private $apib;

    /**
     * @throws \RuntimeException|\Exception
     */
    protected function setUp()
    {
        $this->apib = new Apib(__DIR__ . '/../example/example.apib');
    }

    /**
     * @test
     */
    public function it_should_create_a_apib_instance(): void
    {
        $endpoints = $this->apib->getEndpoints();
        $this->assertCount(4, $endpoints);
    }

    /**
     * @test
     * @dataProvider endpointList
     * @param int $index
     * @param string $name
     * @param string $uri
     * @param string $method
     */
    public function it_should_include_a_valid_endpoint(int $index, string $name, string $uri, string $method): void
    {
        $endpoints = $this->apib->getEndpoints();

        /** @var Endpoint $endpoint */
        $endpoint = $endpoints[$index];
        $this->assertEquals($name, $endpoint->getName());
        $this->assertEquals($uri, $endpoint->getUri());
        $this->assertEquals($method, $endpoint->getMethod());
    }

    public function endpointList(): array
    {
        return [
            [
                'index' => 0,
                'name' => '[GET]/coupons/{id}',
                'uri' => '/coupons/250FF',
                'method' => 'GET',
            ],
            [
                'index' => 1,
                'name' => '[GET]/coupons{?limit}',
                'uri' => '/coupons?limit=10',
                'method' => 'GET',
            ],
            [
                'index' => 2,
                'name' => '[POST]/coupons',
                'uri' => '/coupons',
                'method' => 'POST',
            ],
            [
                'index' => 3,
                'name' => '[PUT]/coupons',
                'uri' => '/coupons',
                'method' => 'PUT',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider endpointAndExampleProvider
     * @param $index
     * @param $request
     * @param $response
     */
    public function it_should_contains_correct_examples($index, $request, $response): void
    {
        $endpoints = $this->apib->getEndpoints();

        /** @var Endpoint $endpoint */
        $endpoint = $endpoints[$index];
        $expected = new Example([new Request($request)], [new Response($response)]);

        $actual = $endpoint->getExamples();
        $this->assertCount(1, $actual);
        $this->assertEquals($expected, $actual[0]);
    }

    public function endpointAndExampleProvider(): array
    {
        return [
            [
                'index' => 0,
                'request' => [
                    'headers' => [
                        ['name' => 'Authorization', 'value' => 'VyX2lkIjoiOTk5OTk5OTkiLCJ0'],
                    ],
                ],
                'response' => [
                    'name' => 200,
                    'headers' => [
                        ['name' => 'Content-Type', 'value' => 'application/json'],
                    ],
                    'body' => "{\n  \"percent_off\": 25,\n  \"redeem_by\": 0,\n  \"id\": \"250FF\",\n  \"created\": 1415203908\n}",
                    'schema' => '{"$schema":"http://json-schema.org/draft-04/schema#","type":"object","properties":{"percent_off":{"type":"number","description":"A positive integer between 1 and 100 that represents the discount the\ncoupon will apply."},"redeem_by":{"type":"number","description":"Date after which the coupon can no longer be redeemed"},"id":{"type":"string"},"created":{"type":"number","description":"Time stamp"}},"required":["id"]}',
                ],
            ],
            [
                'index' => 1,
                'request' => [
                    'headers' => [
                        ['name' => 'Authorization', 'value' => 'VyX2lkIjoiOTk5OTk5OTkiLCJ0'],
                    ],
                ],
                'response' => [
                    'name' => 200,
                    'headers' => [
                        ['name' => 'Content-Type', 'value' => 'application/json'],
                    ],
                    'body' => "[\n  {\n    \"percent_off\": 25,\n    \"redeem_by\": 0,\n    \"id\": \"250FF\",\n    \"created\": 1415203908\n  }\n]",
                    'schema' => '{"$schema":"http://json-schema.org/draft-04/schema#","type":"array"}',
                ],
            ],
            [
                'index' => 2,
                'request' => [
                    'headers' => [
                        ['name' => 'Content-Type', 'value' => 'application/json'],
                        ['name' => 'Authorization', 'value' => 'VyX2lkIjoiOTk5OTk5OTkiLCJ0'],
                    ],
                    'body' => "{\n  \"percent_off\": 25,\n  \"redeem_by\": 0\n}",
                ],
                'response' => [
                    'name' => 200,
                    'headers' => [
                        ['name' => 'Content-Type', 'value' => 'application/json'],
                    ],
                    'body' => "{\n  \"percent_off\": 25,\n  \"redeem_by\": 0,\n  \"id\": \"250FF\",\n  \"created\": 1415203908\n}",
                    'schema' => '{"$schema":"http://json-schema.org/draft-04/schema#","type":"object","properties":{"percent_off":{"type":"number","description":"A positive integer between 1 and 100 that represents the discount the\ncoupon will apply."},"redeem_by":{"type":"number","description":"Date after which the coupon can no longer be redeemed"},"id":{"type":"string"},"created":{"type":"number","description":"Time stamp"}},"required":["id"]}',
                ],
            ],
            [
                'index' => 3,
                'request' => [
                    'headers' => [
                        ['name' => 'Content-Type', 'value' => 'application/json'],
                        ['name' => 'Authorization', 'value' => 'VyX2lkIjoiOTk5OTk5OTkiLCJ0'],
                    ],
                    'body' => "{\n  \"percent_off\": 25,\n  \"redeem_by\": 0\n}",
                ],
                'response' => [
                    'name' => 204,
                    'schema' => '',
                ],
            ],
        ];
    }
}
