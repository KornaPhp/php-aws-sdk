<?php
namespace Aws\Test\Api\Serializer;

use Aws\Api\Service;
use Aws\Command;
use Aws\Api\Serializer\RestJsonSerializer;
use Aws\EndpointV2\Ruleset\RulesetEndpoint;
use Aws\Exception\InvalidJsonException;
use Aws\Test\UsesServiceTrait;
use Psr\Http\Message\RequestInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * @covers Aws\Api\Serializer\RestJsonSerializer
 */
class RestJsonSerializerTest extends TestCase
{
    use UsesServiceTrait;

    private function getTestService(): Service
    {
        return new Service(
            [
                'metadata'=> [
                    'targetPrefix' => 'test',
                    'jsonVersion' => '1.1',
                    'protocol' => 'rest-json',
                    'serviceIdentifier' => 'foo'
                ],
                'operations' => [
                    'foo' => [
                        'http' => ['method' => 'POST'],
                        'input' => ['shape' => 'FooInput'],
                    ],
                    'doctype' => [
                        'http' => ['method' => 'POST'],
                        'input' => ['shape' => 'DocTypeInput'],
                    ],
                    'bar' => [
                        'http' => ['method' => 'POST'],
                        'input' => ['shape' => 'BarInput'],
                    ],
                    'baz' => [
                        'http' => ['method' => 'POST'],
                        'input' => ['shape' => 'BazInput']
                    ],
                    'foobar' => [
                        'http' => ['method' => 'POST'],
                        'input' => ['shape' => 'FooBarInput']
                    ],
                    'qux' => [
                        'http' => ['method' => 'POST'],
                        'input' => ['shape' => 'QuxInput']
                    ],
                    'noPayload' => [
                        'http' => ['method' => 'GET'],
                        'input' => ['shape' => 'NoPayloadInput']
                    ],
                    'boolHeader' => [
                        'http' => ['method' => 'POST'],
                        'input' => ['shape' => 'BoolHeaderInput']
                    ],
                    'requestUriOperation' =>[
                        'http' => [
                            'method' => 'POST',
                            'requestUri' => 'foo/{PathSegment}'
                        ],
                        'input' => ['shape' => 'RequestUriOperationInput'],
                    ],
                    'DocumentTypeAsPayload' => [
                        'name' => 'DocumentTypeAsPayload',
                        'http' => [
                            'method' => 'PUT',
                            'requestUri' => '/DocumentTypeAsPayload',
                            'responseCode' => 200
                        ],
                        'input' => [
                            'shape' => 'DocumentTypeAsPayloadInputOutput'
                        ],
                        'output' => [
                            'shape' => 'DocumentTypeAsPayloadInputOutput'
                        ],
                        'idempotent' => true
                    ]
                ],
                'shapes' => [
                    'FooInput' => [
                        'type' => 'structure',
                        'members' => [
                            'baz' => ['shape' => 'BazShape']
                        ]
                    ],
                    'DocTypeInput' => [
                        'type' => 'structure',
                        'members' => [
                            "DocumentValue" => [
                                "shape" => "DocumentType",
                            ]
                        ]
                    ],
                    'DocumentTypeAsPayloadInputOutput' => [
                        'type' => 'structure',
                        'members' => [
                            'documentValue' => [
                                'shape' => 'DocumentType'
                            ]
                        ],
                        'payload' => 'documentValue'
                    ],
                    'RequestUriOperationInput' => [
                        'required' => ['PathSegment'],
                        'type' => 'structure',
                        'members' => [
                            "PathSegment" => [
                                "shape" => "PathSegmentShape",
                                "location" => 'uri'
                            ],
                            'baz' => ['shape' => 'BazShape']
                        ]
                    ],
                    'DocumentType' => [
                        'type' => 'structure',
                        'members' => [],
                        'document' => true,
                    ],
                    'BarInput' => [
                        'type' => 'structure',
                        'members' => [
                            'baz' => ['shape' => 'BlobShape']
                        ],
                        'payload' => 'baz'
                    ],
                    'BazInput' => [
                        'type' => 'structure',
                        'members' => ['baz' => ['shape' => 'FooInput']],
                        'payload' => 'baz'
                    ],
                    'FooBarInput' => [
                        'type' => 'structure',
                        'members' => [
                            'baz' => [
                                'shape' => 'BazShape',
                                'location' => 'header',
                                'locationname' => 'Bar',
                                'jsonvalue' => true
                            ],
                        ]
                    ],
                    'QuxInput' => [
                        'type' => 'structure',
                        'members' => [
                            'bar' => [
                                'shape' => 'BazShape',
                                'location' => 'header',
                                'locationname' => 'Bar',
                                'jsonvalue' => true
                            ],
                            'baz' => ['shape' => 'BazShape']
                        ]
                    ],
                    'NoPayloadInput' => [
                        'type' => 'structure',
                        'members' => [
                            'baz' => [
                                'shape' => 'BazShape',
                                'location' => 'header',
                                'locationname' => 'Bar',
                                'jsonvalue' => true
                            ]
                        ]
                    ],
                    'BoolHeaderInput' => [
                        'type' => 'structure',
                        'members' => [
                            'bool' => [
                                'shape' => 'BoolShape',
                                'location' => 'header',
                                'locationName' => 'Is-Bool',
                            ],
                        ]
                    ],
                    'BlobShape' => ['type' => 'blob'],
                    'BazShape'  => ['type' => 'string'],
                    'BoolShape' => ['type' => 'boolean'],
                    'PathSegmentShape'  => ['type' => 'string'],
                ]
            ],
            function () {}
        );
    }

    private function getRequest(string $commandName, array $input): RequestInterface
    {
        $service = $this->getTestService();
        $command = new Command($commandName, $input);
        $j = new RestJsonSerializer($service, 'http://foo.com');
        return $j($command);
    }

    private function getPathEndpointRequest(
        string $commandName,
        array $input,
        ?array $options = []
    ): RequestInterface
    {
        $service = $this->getTestService();
        $command = new Command($commandName, $input);
        $path = $options['path'] ?? 'bar';
        $j = new RestJsonSerializer($service, 'http://foo.com/' . $path);
        return $j($command);
    }

    public function testPreparesRequestsWithContentType(): void
    {
        $request = $this->getRequest('foo', ['baz' => 'bar']);
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('http://foo.com/', (string) $request->getUri());
        $this->assertSame('{"baz":"bar"}', (string) $request->getBody());
        $this->assertSame(
            'application/json',
            $request->getHeaderLine('Content-Type')
        );
    }

    public function testPreparesRequestsWithEndpointWithPath(): void
    {
        $request = $this->getPathEndpointRequest('foo', ['baz' => 'bar']);
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('http://foo.com/bar', (string) $request->getUri());
        $this->assertSame('{"baz":"bar"}', (string) $request->getBody());
        $this->assertSame(
            'application/json',
            $request->getHeaderLine('Content-Type')
        );
    }

    public function testPreparesRequestsWithEndpointWithRequestUriAndPath(): void
    {
        $request = $this->getPathEndpointRequest(
            'requestUriOperation',
            ['PathSegment' => 'bar', 'baz' => 'bar']
        );
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('http://foo.com/bar/foo/bar', (string) $request->getUri());
        $this->assertSame('{"baz":"bar"}', (string) $request->getBody());
        $this->assertSame(
            'application/json',
            $request->getHeaderLine('Content-Type')
        );
    }

    public function testPreparesRequestsWithJsonValueTraitString(): void
    {
        $jsonValueArgs = '{"a":"b"}';
        $request = $this->getRequest('foobar', ['baz' => $jsonValueArgs]);
        $this->assertSame('IntcImFcIjpcImJcIn0i', $request->getHeaderLine('baz'));
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('http://foo.com/', (string) $request->getUri());
        $this->assertSame('', $request->getHeaderLine('Content-Type'));
    }

    public function testPreparesRequestsWithJsonValueTraitArray(): void
    {
        $jsonValueArgs = [
            "a" => "b"
        ];
        $request = $this->getRequest('foobar', ['baz' => $jsonValueArgs]);
        $this->assertSame('eyJhIjoiYiJ9', $request->getHeaderLine('baz'));
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('http://foo.com/', (string) $request->getUri());
        $this->assertSame('', $request->getHeaderLine('Content-Type'));
    }

    public function testPreparesRequestsWithJsonValueTraitEmptyString(): void
    {
        $jsonValueArgs = '';
        $request = $this->getRequest('foobar', ['baz' => $jsonValueArgs]);
        $this->assertSame('IiI=', $request->getHeaderLine('baz'));
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('http://foo.com/', (string) $request->getUri());
        $this->assertSame('', $request->getHeaderLine('Content-Type'));
    }

    public function testPreparesRequestsWithJsonValueTraitThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $obj = new \stdClass();
        $obj->obj = $obj;
        $this->getRequest('foobar', ['baz' => $obj]);
    }

    public function testPreparesRequestsWithStructPayload(): void
    {
        $request = $this->getRequest('baz', ['baz' => ['baz' => '1234']]);
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('http://foo.com/', (string) $request->getUri());
        $this->assertSame('{"baz":"1234"}', (string) $request->getBody());
        $this->assertSame(
            'application/json',
            $request->getHeaderLine('Content-Type')
        );
    }

    /**
     * @dataProvider doctypeTestProvider
     * @param string $operation
     *
     */
    public function testHandlesDoctype($input, $expectedOutput): void
    {
        $request = $this->getRequest('doctype', $input);
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('http://foo.com/', (string) $request->getUri());
        $this->assertSame($expectedOutput, $request->getBody()->getContents());
        $this->assertSame(
            'application/json',
            $request->getHeaderLine('Content-Type')
        );
    }


    public function doctypeTestProvider(): iterable
    {
        return [
            [
                ['DocumentValue' =>
                    ['DocumentType' =>
                        [
                            'name' => "John",
                            'age'=> 31,
                            'active'=> true
                        ]
                    ]
                ],
                '{"DocumentValue":{"DocumentType":{"name":"John","age":31,"active":true}}}'
            ],
            [
                ['DocumentValue' =>
                    [
                        'DocumentType' => true
                    ]
                ],
                '{"DocumentValue":{"DocumentType":true}}'
            ],
            [
                ['DocumentValue' =>
                    [
                        'DocumentType' => 2
                    ]
                ],
                '{"DocumentValue":{"DocumentType":2}}'
            ],
        ];
    }


    /**
     * @dataProvider restJsonContentTypeProvider
     * @param string $operation
     * @param string $input
     */
    public function testRestJsonContentTypeNoPayload(
        string $operation,
        array $input
    ): void
    {
        $request = $this->getRequest($operation, $input);
        $this->assertSame('http://foo.com/', (string) $request->getUri());
        $this->assertSame("", $request->getBody()->getContents());
        $this->assertSame(
            "",
            $request->getHeaderLine('Content-Type')
        );
        self::assertEmpty($request->getHeader("Content-length"));
    }


    public function restJsonContentTypeProvider(): iterable
    {
        return [
            [
                "noPayload", ['baz' => 'bar'],
            ],
            [
                "noPayload", [],
            ],
        ];
    }

    /**
     * @dataProvider boolProvider
     * @param bool $arg
     * @param string $expected
     */
    public function testSerializesHeaderValueToBoolString(
        bool $arg,
        string $expected
    ): void
    {
        $request = $this->getRequest('boolHeader', ['bool' => $arg]);
        $this->assertSame($expected, $request->getHeaderLine('Is-Bool'));
    }

    public function boolProvider(): iterable
    {
        return [
            [true, 'true'],
            [false, 'false']
        ];
    }

    public function testDoesNotOverrideScheme(): void
    {
        $serializer = new RestJsonSerializer($this->getTestService(), 'http://foo.com');
        $cmd = new Command('foo', ['baz' => 'bar']);
        $endpoint = new RulesetEndpoint('https://foo.com');
        $request = $serializer($cmd, $endpoint);
        $this->assertSame('http://foo.com/', (string) $request->getUri());
    }

    /**
     * @param string|array $input
     * @param string $expectedOutput
     *
     * @return void
     * @dataProvider handlesDocTypeAsPayloadProvider
     */
    public function testHandlesDocTypeAsPayload(
        string|array $input,
        string $expectedOutput
    ): void
    {
        $request = $this->getRequest('DocumentTypeAsPayload', ['documentValue' => $input]);
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('http://foo.com/DocumentTypeAsPayload', (string) $request->getUri());
        $this->assertSame($expectedOutput, $request->getBody()->getContents());
        $this->assertSame(
            'application/json',
            $request->getHeaderLine('Content-Type')
        );
    }

    public function handlesDocTypeAsPayloadProvider(): \Generator
    {
        yield 'string payload' => ['hello', '"hello"'];
        yield 'simple string field' => [
            ['message' => 'Hello, world!'],
            '{"message":"Hello, world!"}',
        ];
        yield 'numeric and boolean types' => [
            ['success' => true, 'count' => 3, 'ratio' => 0.75],
            '{"success":true,"count":3,"ratio":0.75}',
        ];
        yield 'null value' => [
            ['result' => null],
            '{"result":null}',
        ];
        yield 'empty object' => [
            [],
            '{}',
        ];
        yield 'empty array' => [
            ['items' => []],
            '{"items":[]}',
        ];
        yield 'nested object' => [
            ['user' => ['id' => 1, 'name' => 'Jane']],
            '{"user":{"id":1,"name":"Jane"}}',
        ];
        yield 'array of objects' => [
            ['records' => [['id' => 1], ['id' => 2]]],
            '{"records":[{"id":1},{"id":2}]}',
        ];
        yield 'deeply nested structure' => [
            ['a' => ['b' => ['c' => ['d' => 123]]]],
            '{"a":{"b":{"c":{"d":123}}}}',
        ];
        yield 'mixed types in array' => [
            ['data' => ['string', 123, true, null]],
            '{"data":["string",123,true,null]}',
        ];
    }

    /**
     * @param $input
     *
     * @return void
     * @dataProvider rejectsInvalidJsonAsPayloadProvider
     */
    public function testRejectsInvalidJsonAsPayload(array|string $input): void
    {
        $this->expectException(InvalidJsonException::class);
        $this->expectExceptionMessage('Unable to encode JSON document');
        $this->getRequest('DocumentTypeAsPayload', ['documentValue' => $input]);
    }

    public function rejectsInvalidJsonAsPayloadProvider(): iterable
    {
        return [
            'malformed byte sequence' => ["\xB1\x31"],
            'invalid continuation byte' => ["\xC3\x28"],
            'overlong encoding' => ["\xE2\x28\xA1"],
            'invalid UTF-8 in nested array' => [
                'users' => [
                    ['name' => "Valid Name"],
                    ['name' => "\xB1\x31"]  // invalid UTF-8
                ]
            ]
        ];
    }
}
