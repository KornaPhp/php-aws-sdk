<?php
namespace Aws\Test;

use Aws\Api\Service;
use Aws\Auth\AuthSchemeResolverInterface;
use Aws\ClientResolver;
use Aws\ClientSideMonitoring\Configuration;
use Aws\ClientSideMonitoring\ConfigurationProvider;
use Aws\Configuration\ConfigurationResolver;
use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\Credentials\CredentialsInterface;
use Aws\DynamoDb\DynamoDbClient;
use Aws\Endpoint\Partition;
use Aws\EndpointV2\EndpointProviderV2;
use Aws\Exception\InvalidRegionException;
use Aws\LruArrayCache;
use Aws\S3\S3Client;
use Aws\HandlerList;
use Aws\Result;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * @covers Aws\ClientResolver
 */
class ClientResolverTest extends TestCase
{
    use UsesServiceTrait;

    public function testEnsuresRequiredArgumentsAreProvided()
    {
        $this->expectExceptionMessage("Missing required client configuration options");
        $this->expectException(\InvalidArgumentException::class);
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $r->resolve([], new HandlerList());
    }

    /** @doesNotPerformAssertions */
    public function testAddsValidationSubscriber()
    {
        $c = new DynamoDbClient([
            'region' => 'x',
            'version' => 'latest'
        ]);

        try {
            // CreateTable requires actual input parameters.
            $c->createTable([]);
            $this->fail('Did not validate');
        } catch (\InvalidArgumentException $e) {
        }
    }

    /** @doesNotPerformAssertions */
    public function testCanDisableValidation()
    {
        $c = new DynamoDbClient([
            'region' => 'x',
            'version' => 'latest',
            'validate' => false
        ]);
        $command = $c->getCommand('CreateTable');
        $handler = \Aws\constantly(new Result([]));
        $command->getHandlerList()->setHandler($handler);
        $c->execute($command);
    }

    /** @doesNotPerformAssertions */
    public function testCanDisableSpecificValidationConstraints()
    {
        $c = new DynamoDbClient([
            'region' => 'x',
            'version' => 'latest',
            'validate' => [
                'min' => true,
                'max' => true,
                'required' => false
            ]
        ]);
        $command = $c->getCommand('CreateTable');
        $handler = \Aws\constantly(new Result([]));
        $command->getHandlerList()->setHandler($handler);
        $c->execute($command);
    }

    public function testAppliesLegacyDefaults()
    {
        $c = new DynamoDbClient([
            'region' => 'us-east-1',
            'version' => 'latest',
        ]);
        self::assertFalse(isset($c->getConfig()['retries']));
        self::assertFalse(isset($c->getConfig()['sts_regional_endpoints']));
    }

    public function testAppliesApiProvider()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $provider = function () {
            return ['metadata' => ['protocol' => 'query']];
        };
        $conf = $r->resolve([
            'service' => 'dynamodb',
            'region' => 'x',
            'api_provider' => $provider,
            'version' => 'latest'
        ], new HandlerList());
        $this->assertArrayHasKey('api', $conf);
        $this->assertArrayHasKey('error_parser', $conf);
        $this->assertArrayHasKey('serializer', $conf);
    }

    public function testAppliesApiProviderSigningNameToConfig()
    {
        $signingName = 'foo';
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'dynamodb',
            'region' => 'x',
            'api_provider' => function () use ($signingName) {
                return ['metadata' => [
                    'protocol' => 'query',
                    'signingName' => $signingName,
                ]];
            },
            'version' => 'latest'
        ], new HandlerList());
        $this->assertSame($conf['config']['signing_name'], $signingName);
    }

    public function testAppliesUseAwsSharedFilesTOConfig()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'dynamodb',
            'region' => 'x',
            'use_aws_shared_config_files' => false,
            'version' => 'latest'
        ], new HandlerList());
        $this->assertSame($conf['use_aws_shared_config_files'], false);
    }

    public function testAppliesEndpointProviderV2()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'dynamodb',
            'region' => 'x',
            'version' => 'latest'
        ], new HandlerList());
        $this->assertInstanceOf(
            EndpointProviderV2::class,
            $conf['endpoint_provider']
        );
    }

    public function testAppliesClientContextParams()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Invalid configuration value provided for "Accelerate". Expected boolean, but got string(3) "foo"'
            . "\n\n" . 'Accelerate: (boolean)' . "\n\n"
            . '  Enables this client to use S3 Transfer Acceleration endpoints.'
        );
        $conf = $r->resolve([
            'service' => 's3',
            'version' => 'latest',
            'region' => 'x',
            'Accelerate' => 'foo',
        ], new HandlerList());
    }

    public function testPrefersApiProviderNameToPartitionName()
    {
        $signingName = 'foo';
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'dynamodb',
            'region' => 'x',
            'api_provider' => function () use ($signingName) {
                return ['metadata' => [
                    'protocol' => 'query',
                    'signingName' => $signingName,
                ]];
            },
            'endpoint_provider' => function () use ($signingName) {
                return [
                    'endpoint' => 'https://www.amazon.com',
                    'signingName' => "not_$signingName",
                ];
            },
            'version' => 'latest'
        ], new HandlerList());
        $this->assertSame($conf['config']['signing_name'], $signingName);
    }

    public function testValidatesInput()
    {
        $this->expectExceptionMessage("Invalid configuration value provided for \"foo\". Expected string, but got int(-1)");
        $this->expectException(\InvalidArgumentException::class);
        $r = new ClientResolver([
            'foo' => [
                'type' => 'value',
                'valid' => ['string']
            ]
        ]);
        $r->resolve(['foo' => -1], new HandlerList());
    }

    public function testValidatesCallables()
    {
        $this->expectExceptionMessage("Invalid configuration value provided for \"foo\". Expected callable, but got string(1) \"c\"");
        $this->expectException(\InvalidArgumentException::class);
        $r = new ClientResolver([
            'foo' => [
                'type' => 'value',
                'valid' => ['callable']
            ]
        ]);
        $r->resolve(['foo' => 'c'], new HandlerList());
    }

    public function testValidatesCallableClosure()
    {
        $r = new ClientResolver([
            'foo' => [
                'type' => 'value',
                'valid' => ['string'],
                'default' => function () {
                    return 'callable_test';
                }
            ]
        ]);
        $res = $r->resolve([], new HandlerList());
        $this->assertSame('callable_test', $res['foo']);
    }

    public static function checkCallable()
    {
        return "testcall";
    }

    public function testValidatesNotInvokeStringCallable()
    {
        $callableFunction = '\Aws\Test\ClientResolverTest::checkCallable';
        $r = new ClientResolver([
            'foo' => [
                'type' => 'value',
                'valid' => ['string'],
                'default' => $callableFunction
            ]
        ]);
        $res = $r->resolve([], new HandlerList());
        $this->assertIsCallable($callableFunction);
        $this->assertSame(
            '\Aws\Test\ClientResolverTest::checkCallable',
            $res['foo']
        );
    }

    public function testValidatesCredentials()
    {
        $this->expectExceptionMessage("Credentials must be an");
        $this->expectException(\InvalidArgumentException::class);
        $r = new ClientResolver([
            'credentials' => ClientResolver::getDefaultArguments()['credentials']
        ]);
        $r->resolve(['credentials' => []], new HandlerList());
    }

    public function testLoadsFromDefaultChainIfNeeded()
    {
        $key = getenv(CredentialProvider::ENV_KEY);
        $secret = getenv(CredentialProvider::ENV_SECRET);
        putenv(CredentialProvider::ENV_KEY . '=foo');
        putenv(CredentialProvider::ENV_SECRET . '=bar');
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'version' => 'latest'
        ], new HandlerList());
        $c = call_user_func($conf['credentials'])->wait();
        $this->assertInstanceOf(CredentialsInterface::class, $c);
        $this->assertSame('foo', $c->getAccessKeyId());
        $this->assertSame('bar', $c->getSecretKey());
        putenv(CredentialProvider::ENV_KEY . "=$key");
        putenv(CredentialProvider::ENV_SECRET . "=$secret");
    }

    public function testCreatesFromArray()
    {
        $exp = time() + 500;
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'version' => 'latest',
            'credentials' => [
                'key' => 'foo',
                'secret' => 'baz',
                'token' => 'tok',
                'expires' => $exp
            ]
        ], new HandlerList());
        $creds = call_user_func($conf['credentials'])->wait();
        $this->assertSame('foo', $creds->getAccessKeyId());
        $this->assertSame('baz', $creds->getSecretKey());
        $this->assertSame('tok', $creds->getSecurityToken());
        $this->assertSame($exp, $creds->getExpiration());
    }

    /** @doesNotPerformAssertions */
    public function testCanDisableRetries()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $r->resolve([
            'service' => 's3',
            'region' => 'baz',
            'version' => 'latest',
            'retries' => 0,
        ], new HandlerList());
    }

    /** @doesNotPerformAssertions */
    public function testCanEnableRetries()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $r->resolve([
            'service' => 's3',
            'region' => 'baz',
            'version' => 'latest',
            'retries' => 2,
        ], new HandlerList());
    }

    /** @doesNotPerformAssertions */
    public function testCanEnableRetriesStandardMode()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $r->resolve([
            'service' => 's3',
            'region' => 'baz',
            'version' => 'latest',
            'retries' => [
                'mode' => 'standard',
                'max_attempts' => 10,
            ]
        ], new HandlerList());
    }

    /** @doesNotPerformAssertions */
    public function testCanEnableRetriesAdaptivedMode()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $r->resolve([
            'service' => 's3',
            'region' => 'baz',
            'version' => 'latest',
            'retries' => [
                'mode' => 'adaptive',
                'max_attempts' => 10,
            ]
        ], new HandlerList());
    }

    public function testCanCreateNullCredentials()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'credentials' => false,
            'version' => 'latest'
        ], new HandlerList());
        $creds = call_user_func($conf['credentials'])->wait();
        $this->assertInstanceOf(Credentials::class, $creds);
        $this->assertSame('anonymous', $conf['config']['signature_version']);
    }

    public function testCanCreateCredentialsFromProvider()
    {
        $c = new Credentials('foo', 'bar');
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'credentials' => function () use ($c) {
                return \GuzzleHttp\Promise\Create::promiseFor($c);
            },
            'version' => 'latest'
        ], new HandlerList());
        $this->assertSame($c, call_user_func($conf['credentials'])->wait());
    }

    public function testCanCreateCredentialsFromProfile()
    {
        $dir = sys_get_temp_dir() . '/.aws';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $ini = <<<EOT
[foo]
aws_access_key_id = foo
aws_secret_access_key = baz
aws_session_token = tok
EOT;
        file_put_contents($dir . '/credentials', $ini);
        $home = getenv('HOME');
        putenv('HOME=' . dirname($dir));
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'profile' => 'foo',
            'version' => 'latest'
        ], new HandlerList());
        $creds = call_user_func($conf['credentials'])->wait();
        $this->assertSame('foo', $creds->getAccessKeyId());
        $this->assertSame('baz', $creds->getSecretKey());
        $this->assertSame('tok', $creds->getSecurityToken());
        unlink($dir . '/credentials');
        putenv("HOME=$home");
    }

    public function testCanUseCredentialsObject()
    {
        $c = new Credentials('foo', 'bar');
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'credentials' => $c,
            'version' => 'latest'
        ], new HandlerList());
        $this->assertSame($c, call_user_func($conf['credentials'])->wait());
    }

    public function testCanUseCredentialsCache()
    {
        putenv('AWS_CONTAINER_CREDENTIALS_RELATIVE_URI');
        unset($_SERVER['AWS_CONTAINER_CREDENTIALS_RELATIVE_URI']);
        $credentialsEnvironment = [
            'home' => 'HOME',
            'key' => CredentialProvider::ENV_KEY,
            'secret' => CredentialProvider::ENV_SECRET,
            'session' => CredentialProvider::ENV_SESSION,
            'profile' => CredentialProvider::ENV_PROFILE,
        ];
        $envState = [];
        foreach ($credentialsEnvironment as $key => $envVariable) {
            $envState[$key] = getenv($envVariable);
            putenv("$envVariable=");
        }

        $c = new Credentials('foo', 'bar');
        $cache = new LruArrayCache;
        $cache->set('aws_cached_instance_credentials', $c);
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'credentials' => $cache,
            'version' => 'latest'
        ], new HandlerList());

        $cached = call_user_func($conf['credentials'])->wait();

        foreach ($credentialsEnvironment as $key => $envVariable) {
            putenv("$envVariable={$envState[$key]}");
        }

        $this->assertSame($c, $cached);
    }

    public function testCanUseCsmConfigObject()
    {
        $config = new Configuration(true, 'foohost', 1111, 'barid');
        $resolver = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $resolver->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'csm' => $config,
            'version' => 'latest'
        ], new HandlerList());
        $this->assertEquals($config->toArray(), $conf['csm']->toArray());
    }

    public function testCanUseCsmArray()
    {
        $config = new Configuration(true, 'foohost', 1111, 'barid');
        $configArray = $config->toArray();
        $resolver = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $resolver->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'csm' => $configArray,
            'version' => 'latest'
        ], new HandlerList());
        $this->assertEquals($configArray, $conf['csm']);
    }

    public function testCanUseCsmFalse()
    {
        $config = new Configuration(
            false,
            ConfigurationProvider::DEFAULT_HOST,
            ConfigurationProvider::DEFAULT_PORT,
            ConfigurationProvider::DEFAULT_CLIENT_ID
        );
        $resolver = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $resolver->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'csm' => false,
            'version' => 'latest'
        ], new HandlerList());
        $this->assertEquals($config->toArray(), $conf['csm']->toArray());
    }

    public function testCanUseCustomEndpointProviderWithExtraData()
    {
        $p = function () {
            return [
                'endpoint' => 'http://foo.com',
                'signatureVersion' => 'v4'
            ];
        };
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'endpoint_provider' => $p,
            'version' => 'latest'
        ], new HandlerList());
        $this->assertSame('v4', $conf['config']['signature_version']);
    }

    public function testCanPassStsRegionalEndpointsToEndpointProvider()
    {
        $data = json_decode(
            file_get_contents(__DIR__ . '/Endpoint/fixtures/sts_regional_endpoints.json'),
            true
        );
        $partition = new Partition($data['partitions'][0]);
        $resolver = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $resolver->resolve([
            'service' => 'sts',
            'region' => 'us-west-2',
            'sts_regional_endpoints' => 'regional',
            'version' => 'latest',
            'endpoint_provider' => $partition
        ], new HandlerList());

        $this->assertSame(
            'https://sts.us-west-2.amazonaws.com',
            $conf['endpoint']
        );
    }

    /**
     * @dataProvider dualStackEndpointCases
     *
     * @param $service
     * @param $useDualstackEndpoint
     * @param $useFipsEndpoint
     * @param $region
     * @param $expectedEndpoint
     */
    public function testDualstackEndpoints(
        $service,
        $useDualstackEndpoint,
        $useFipsEndpoint,
        $region,
        $expectedEndpoint
    )
    {
        $resolver = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $resolver->resolve([
            'service' => $service,
            'region' => $region,
            'use_dual_stack_endpoint' => $useDualstackEndpoint,
            'use_fips_endpoint' => $useFipsEndpoint,
            'version' => 'latest',
        ], new HandlerList());

        $this->assertSame(
            'https://' . $expectedEndpoint,
            $conf['endpoint']
        );
    }

    public function dualStackEndpointCases()
    {
        return [
            ["ec2", false, false, "us-west-2", "ec2.us-west-2.amazonaws.com",],
            ["ec2", false, false, "us-east-2", "ec2.us-east-2.amazonaws.com",],
            ["ec2", true, false, "us-west-2", "ec2.us-west-2.api.aws",],
            ["ec2", true, false, "us-east-2", 'ec2.us-east-2.api.aws',],
            ["s3", false, false, "us-west-2", "s3.us-west-2.amazonaws.com",],
            ["s3", false, false, "us-east-2", "s3.us-east-2.amazonaws.com",],
            ["s3", true, false, "us-west-2", 's3.dualstack.us-west-2.amazonaws.com'],
            ["s3", true, false, "us-east-2", "s3.dualstack.us-east-2.amazonaws.com",],
            ["route53", false, false, "us-west-2", "route53.amazonaws.com",],
            ["route53", false, false, "us-east-2", "route53.amazonaws.com",],
            ["route53", true, false, "us-west-2", "route53.us-west-2.api.aws",],
            ["route53", true, false, "us-east-2", 'route53.us-east-2.api.aws',],
            ["dynamodb", false, false, "us-west-2", "dynamodb.us-west-2.amazonaws.com",],
            ["dynamodb", false, false, "us-east-2", "dynamodb.us-east-2.amazonaws.com",],
            ["dynamodb", true, false, "us-west-2", "dynamodb.us-west-2.api.aws",],
            ["dynamodb", true, false, "us-east-2", "dynamodb.us-east-2.api.aws",],
            ["dynamodb", false, true, "us-west-2", "dynamodb-fips.us-west-2.amazonaws.com",],
            ["dynamodb", true, true, "us-west-2", "dynamodb-fips.us-west-2.api.aws",],
        ];
    }

    public function testDualstackEndpointInIsoPartition()
    {
        $resolver = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $resolver->resolve([
            'service' => 'ec2',
            'region' => 'us-iso-east-1',
            'use_dual_stack_endpoint' => false,
            'use_fips_endpoint' => false,
            'version' => 'latest',
        ], new HandlerList());
        $this->assertSame(
            'https://ec2.us-iso-east-1.c2s.ic.gov',
            $conf['endpoint']
        );
    }

    public function testDualstackEndpointFailureOnDualstackNotSupported()
    {
        $this->expectException(\Aws\Endpoint\UseDualstackEndpoint\Exception\ConfigurationException::class);
        $this->expectExceptionMessage("Dual-stack is not supported in ISO regions");
        $resolver = new ClientResolver(ClientResolver::getDefaultArguments());
        $resolver->resolve([
            'service' => 'ec2',
            'region' => 'us-iso-east-1',
            'use_dual_stack_endpoint' => true,
            'use_fips_endpoint' => false,
            'version' => 'latest',
        ], new HandlerList());
    }

    /**
     * @dataProvider s3EndpointCases
     *
     * @param $config
     * @param $endpoint
     */
    public function testCanPassS3RegionalEndpointToEndpointProvider($config, $endpoint)
    {
        $data = json_decode(
            file_get_contents(__DIR__ . '/Endpoint/fixtures/s3_us_east_1_regional_endpoint.json'),
            true
        );
        $partition = new Partition($data['partitions'][0]);
        $resolver = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $resolver->resolve([
            'service' => 's3',
            'region' => 'us-east-1',
            's3_us_east_1_regional_endpoint' => $config,
            'version' => 'latest',
            'endpoint_provider' => $partition
        ], new HandlerList());
        $this->assertEquals($endpoint, $conf['endpoint']);
    }

    public function s3EndpointCases()
    {
        return [
            ['regional', 'https://s3.us-east-1.amazonaws.com'],
            ['legacy', 'https://s3.amazonaws.com'],
        ];
    }

    /** @doesNotPerformAssertions */
    public function testAddsLoggerWithDebugSettings()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'retry_logger' => 'debug',
            'endpoint' => 'http://us-east-1.foo.amazonaws.com',
            'version' => 'latest'
        ], new HandlerList());
    }

    /** @doesNotPerformAssertions */
    public function testAddsDebugListener()
    {
        $em = new HandlerList();
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'debug' => true,
            'endpoint' => 'http://us-east-1.foo.amazonaws.com',
            'version' => 'latest'
        ], $em);
    }

    public function canSetDebugToFalse()
    {
        $em = new HandlerList();
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'debug' => false,
            'endpoint' => 'http://us-east-1.foo.amazonaws.com',
            'version' => 'latest'
        ], $em);
    }

    public function testCanAddHttpClientDefaultOptions()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'version' => 'latest',
            'http' => ['foo' => 'bar']
        ], new HandlerList());
        $this->assertSame('bar', $conf['http']['foo']);
    }

    public function testCanAddConfigOptions()
    {
        $c = new S3Client([
            'version' => 'latest',
            'region' => 'us-west-2',
            'bucket_endpoint' => true,
        ]);
        $this->assertTrue($c->getConfig('bucket_endpoint'));
    }

    /** @doesNotPerformAssertions */
    public function testSkipsNonRequiredKeys()
    {
        $r = new ClientResolver([
            'foo' => [
                'valid' => ['int'],
                'type' => 'value'
            ]
        ]);
        $r->resolve([], new HandlerList());
    }

    public function testAppliesLatestAsDefaultVersionWithoutSuppliedVersion()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'ec2',
            'region' => 'us-west-2',
        ], new HandlerList());
        self::assertSame('latest', $conf['version']);
    }

    public function testAppliesVersion()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'ec2',
            'region' => 'us-west-2',
            'version' => '2015-10-01'
        ], new HandlerList());
        self::assertSame('2015-10-01', $conf['version']);
    }

    public function testHasSpecificMessageForMissingRegion()
    {
        $this->expectExceptionMessage("A \"region\" configuration value is required for the \"foo\" service");
        $this->expectException(\InvalidArgumentException::class);
        $args = ClientResolver::getDefaultArguments()['region'];
        $r = new ClientResolver(['region' => $args]);
        $r->resolve(['service' => 'foo'], new HandlerList());
    }

    public function testHasSpecificMessageForNullRequiredRegion()
    {
        $this->expectExceptionMessage("A \"region\" configuration value is required for the \"foo\" service");
        $this->expectException(\InvalidArgumentException::class);
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $list = new HandlerList();
        $r->resolve([
            'service' => 'foo',
            'region' => null,
            'credentials' => ['key' => 'a', 'secret' => 'b'],
            'version' => 'latest',
        ], $list);
    }

    public function testAddsTraceMiddleware()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $list = new HandlerList();
        $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'credentials' => ['key' => 'a', 'secret' => 'b'],
            'version' => 'latest',
            'debug' => ['logfn' => function ($value) use (&$str) {
                $str .= $value;
            }]
        ], $list);
        $value = $this->getPropertyValue($list, 'interposeFn');
        $this->assertIsCallable($value);
    }

    public function testAppliesUserAgent()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $list = new HandlerList();
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'credentials' => ['key' => 'a', 'secret' => 'b'],
            'version' => 'latest',
            'ua_append' => 'PHPUnit/Unit',
        ], $list);
        $this->assertArrayHasKey('ua_append', $conf);
        $this->assertIsArray($conf['ua_append']);
        $this->assertContains('PHPUnit/Unit', $conf['ua_append']);
    }

    /**
     * @dataProvider statValueProvider
     * @param bool|array $userValue
     * @param array $resolvedValue
     */
    public function testAcceptsBooleansAndArraysForSelectiveStatCollection($userValue, array $resolvedValue)
    {
        $list = new HandlerList;
        $args = [];
        ClientResolver::_apply_stats($userValue, $args, $list);
        foreach ($resolvedValue as $collector => $enabled) {
            $this->assertArrayHasKey($collector, $args['stats']);
            $this->assertSame($enabled, $args['stats'][$collector]);
        }
    }

    public function statValueProvider()
    {
        return [
            [
                // Value provided for all stat collectors
                ['http' => false, 'retries' => true, 'timer' => false],
                ['http' => false, 'retries' => true, 'timer' => false],
            ],
            [
                // Value provided for a subset of stat collectors
                ['retries' => true],
                ['http' => false, 'retries' => true, 'timer' => false],
            ],
            [
                // Boolean false
                false,
                ['http' => false, 'retries' => false, 'timer' => false],
            ],
            [
                // Boolean true
                true,
                ['http' => true, 'retries' => true, 'timer' => true],
            ],
        ];
    }

    /**
     * @dataProvider endpointProviderReturnProvider
     *
     * @param array $args
     * @param string $argName
     * @param string $expected
     * @param string $override
     */
    public function testResolvesValuesReturnedByEndpointProvider(
        array $args,
              $argName,
              $expected,
              $override
    )
    {
        $resolverArgs = array_intersect_key(
            ClientResolver::getDefaultArguments(),
            array_flip(['endpoint_provider', 'service', 'region', 'scheme', $argName])
        );
        $resolver = new ClientResolver($resolverArgs);

        $resolved = $resolver->resolve($args, new HandlerList);
        $this->assertSame($expected, $resolved[$argName]);

        $resolved = $resolver->resolve([$argName => $override] + $args, new HandlerList);
        $this->assertSame($override, $resolved[$argName]);
    }

    public function endpointProviderReturnProvider()
    {
        $partition = new Partition([
            'partition' => 'aws-test',
            'dnsSuffix' => 'amazonaws.com',
            'regions' => [],
            'services' => [
                'foo' => [
                    'endpoints' => [
                        'bar' => [
                            'credentialScope' => [
                                'service' => 'baz',
                                'region' => 'quux',
                            ],
                            'signatureVersions' => ['anonymous'],
                        ],
                    ],
                ],
            ],
        ]);
        $invocationArgs = [
            'endpoint_provider' => $partition,
            'service' => 'foo',
            'region' => 'bar',
        ];

        return [
            // signatureVersion
            [$invocationArgs, 'signature_version', 'anonymous', 'v4'],
            // signingName
            [$invocationArgs, 'signing_name', 'baz', 'fizz'],
            // signingRegion
            [$invocationArgs, 'signing_region', 'quux', 'buzz'],
        ];
    }


    /**
     * @dataProvider partitionReturnProvider
     *
     * @param array $args
     * @param string $argName
     * @param string $expected
     */
    public function testSigningValuesAreFetchedFromPartition(
        array $args,
              $argName,
              $expected
    )
    {
        $resolverArgs = array_intersect_key(
            ClientResolver::getDefaultArguments(),
            array_flip(['endpoint_provider', 'endpoint', 'service', 'region', $argName])
        );
        $resolver = new ClientResolver($resolverArgs);

        $resolved = $resolver->resolve($args, new HandlerList);
        $this->assertSame($expected, $resolved[$argName]);
    }

    public function partitionReturnProvider()
    {
        $invocationArgs = ['endpoint' => 'https://foo.bar.amazonaws.com'];

        return [
            // signatureVersion
            [
                ['service' => 's3', 'region' => 'us-west-2'] + $invocationArgs,
                'signature_version',
                's3v4',
            ],
            // signingName
            [
                ['service' => 'iot', 'region' => 'us-west-2'] + $invocationArgs,
                'signing_name',
                'iot',
            ],
            // signingRegion
            [
                ['service' => 'dynamodb', 'region' => 'local'] + $invocationArgs,
                'signing_region',
                'us-east-1',
            ],
        ];
    }

    /**
     * @dataProvider idempotencyAutoFillProvider
     *
     * @param mixed $value
     * @param bool $shouldAddIdempotencyMiddleware
     */
    public function testIdempotencyTokenMiddlewareAddedAsAppropriate(
        $value,
        $shouldAddIdempotencyMiddleware
    )
    {
        $args = [
            'api' => new Service([], function () {
                return [];
            }),
        ];
        $list = new HandlerList;

        $this->assertCount(0, $list);
        ClientResolver::_apply_idempotency_auto_fill($value, $args, $list);
        $this->assertCount($shouldAddIdempotencyMiddleware ? 1 : 0, $list);
    }

    public function idempotencyAutoFillProvider()
    {
        return [
            [true, true],
            [false, false],
            ['truthy', false],
            ['openssl_random_pseudo_bytes', true],
            [function ($length) {
                return 'foo';
            }, true],
        ];
    }

    /**
     * @dataProvider validateRegionProvider
     *
     * @param $region
     * @param $expected
     */
    public function testValidatesRegion($region, $expected)
    {
        $resolver = new ClientResolver(ClientResolver::getDefaultArguments());
        try {
            $result = $resolver->resolve(
                [
                    'service' => 's3',
                    'version' => 'latest',
                    'region' => $region
                ],
                new HandlerList()
            );

            if ($expected instanceof \Exception) {
                $this->fail('Expected an exception with: ' . $expected->getMessage());
            }
            $this->assertEquals($expected, $result['region']);

        } catch (InvalidRegionException $e) {
            $this->assertEquals($expected->getMessage(), $e->getMessage());
        }
    }

    public function validateRegionProvider()
    {
        return [
            [
                'us-west-2',
                'us-west-2',
            ],
            [
                'x',
                'x',
            ],
            [
                'hosthijack.com/',
                new InvalidRegionException('Region must be a valid RFC host label.'),
            ],
        ];
    }

    public function invalidDisableRequestCompressionValues()
    {
        return [
            ['foo'],
            [ 1 ],
            [function () {return 'nothing';}]
        ];
    }

    /**
     * @dataProvider invalidDisableRequestCompressionValues
     */
    public function testInvalidDisableRequestCompressionTypeThrowsException($invalidType)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Invalid configuration value provided/');
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $list = new HandlerList();
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'credentials' => ['key' => 'a', 'secret' => 'b'],
            'version' => 'latest',
            'disable_request_compression' => $invalidType
        ], $list);
        $this->assertArrayHasKey('disable_request_compression', $conf);
        $this->assertFalse($conf['disable_request_compression']);
    }

    public function testDisableRequestCompressionDefault()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $list = new HandlerList();
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'credentials' => ['key' => 'a', 'secret' => 'b'],
            'version' => 'latest',
        ], $list);
        $this->assertArrayHasKey('disable_request_compression', $conf);
        $this->assertFalse($conf['disable_request_compression']);
    }

    public function invalidMinCompressionSizeValues()
    {
        return [
            [ true ],
            [ 'foo' ],
            [function () {return 'nothing';}],
            [ 99999999 ],
            [ -1 ]
        ];
    }

    /**
     * @dataProvider invalidMinCompressionSizeValues
     */
    public function testInvalidMinCompressionSizeValues($invalidType)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Invalid configuration value provided/');
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $list = new HandlerList();
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'credentials' => ['key' => 'a', 'secret' => 'b'],
            'version' => 'latest',
            'request_min_compression_size_bytes' => $invalidType
        ], $list);
    }

    public function testMinCompressionSizeDefault()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $list = new HandlerList();
        $conf = $r->resolve([
            'service' => 'sqs',
            'region' => 'x',
            'credentials' => ['key' => 'a', 'secret' => 'b'],
            'version' => 'latest',
        ], $list);
        $this->assertArrayHasKey('request_min_compression_size_bytes', $conf);
        $this->assertEquals(10240, $conf['request_min_compression_size_bytes']);
    }

    /**
     * @dataProvider configResolutionProvider
     *
     * @param $ini
     * @param $env
     * @param $expected
     * @param $configKey
     * @param $configType
     */
    public function testConfigResolutionOrder($ini, $env, $expected, $configKey, $configType)
    {
        $dir = sys_get_temp_dir() . '/.aws';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if ($env) {
            putenv($env['key'] . '=' . $env['value']);
        }

        file_put_contents($dir . '/config', $ini);
        $home = getenv('HOME');
        putenv('HOME=' . dirname($dir));
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 's3',
            'region' => $configKey === 'region' ? null : 'x'
        ], new HandlerList());

        if ($configKey === 'endpoint') {
            $this->assertTrue(isset($conf['config']['configured_endpoint_url']));
        }

        if ($configType === 'args') {
            $this->assertEquals($conf[$configKey], $expected);
        } else {
            $this->assertEquals($conf['config'][$configKey], $expected);
        }
        unlink($dir . '/config');
        putenv("HOME=$home");
        if ($env) {
            putenv($env['key'] . '=');
        }
    }

    public function configResolutionProvider()
    {
        return [
            [
                <<<EOT
[default]
region = foo-region
EOT
                ,
                ['key' => 'AWS_REGION', 'value' => 'bar-region'],
                'bar-region',
                'region',
                'args'
            ],
            [
                <<<EOT
[default]
region = 'foo-region'
EOT
                    ,
                    null,
                    'foo-region',
                    'region',
                    'args'
                ],
            [
                <<<EOT
[default]
endpoint_url = https://foo-bar.com
services = my-services
[services my-services]
s3 =
  endpoint_url = https://test-foo.com
EOT
                ,
                ['key' => 'AWS_ENDPOINT_URL_S3', 'value' => 'https://test.com'],
                'https://test.com',
                'endpoint',
                'args'
            ],
            [
                <<<EOT
[default]
endpoint_url = https://foo-bar.com
services = my-services
[services my-services]
s3 =
  endpoint_url = https://test-foo.com
EOT
                ,
                null,
                'https://test-foo.com',
                'endpoint',
                'args'
            ],
            [
                <<<EOT
[default]
endpoint_url = https://foo-bar.com
EOT
                ,
                ['key' => 'AWS_ENDPOINT_URL', 'value' => 'https://baz.com'],
                'https://baz.com',
                'endpoint',
                'args'
            ],
            [
                <<<EOT
[default]
endpoint_url = https://foo-bar.com
EOT
                ,
                null,
                'https://foo-bar.com',
                'endpoint',
                'args'
            ]
        ];
    }

    public function testIgnoreConfiguredEndpointUrls()
    {
        $ini = <<<EOT
[default]
endpoint_url = https://foo-bar.com
services = my-services
[services my-services]
s3 =
  endpoint_url = https://test-foo.com
EOT;
        $dir = sys_get_temp_dir() . '/.aws';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        putenv('AWS_ENDPOINT_URL' . '=' . 'https://test-foo.com');
        putenv('AWS_ENDPOINT_URL_S3' . '=' . 'https://test-service-foo.com');

        file_put_contents($dir . '/config', $ini);
        $home = getenv('HOME');
        putenv('HOME=' . dirname($dir));
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 's3',
            'region' => 'x',
            'version' => 'latest',
            'ignore_configured_endpoint_urls' => true
        ], new HandlerList());
        $this->assertFalse(isset($conf['config']['configured_endpoint_url']));
        $this->assertFalse(isset($conf['config']['endpoint']));
        unlink($dir . '/config');
        putenv("HOME=$home");
        putenv('AWS_ENDPOINT_URL' . '=');
        putenv('AWS_ENDPOINT_URL_S3' . '=');
    }

    public function testResolvesAppIdFromClientConfig()
    {
        $appId = 'TestAppId';
        $s3 = new S3Client([
            'region' => 'us-east-1',
            'app_id' => $appId,
            'http_handler' => function (RequestInterface $request) use ($appId) {
                $userAgentValues = explode(' ', $request->getHeader('user-agent')[0]);
                $expectedHeader = "app/$appId";
                $idx = array_search($expectedHeader, $userAgentValues);
                $this->assertNotFalse($idx);
                $this->assertEquals($expectedHeader, $userAgentValues[$idx]);

                return new Response;
            }
        ]);
        $s3->listBuckets();
    }

    public function testResolvesAppIdSourcedFromEnv()
    {
        $currentAppIdFromEnv = getenv('AWS_SDK_UA_APP_ID');
        $deferTask = function () use ($currentAppIdFromEnv) {
            if (!empty($currentAppIdFromEnv)) {
                putenv("AWS_SDK_UA_APP_ID=$currentAppIdFromEnv");
            }
        };

        try {
            $appId = 'TestAppId';
            putenv("AWS_SDK_UA_APP_ID=$appId");
            $s3 = new S3Client([
                'region' => 'us-east-1',
                'http_handler' => function (RequestInterface $request) use ($appId) {
                    $userAgentValues = explode(' ', $request->getHeader('user-agent')[0]);
                    $expectedHeader = "app/$appId";
                    $idx = array_search($expectedHeader, $userAgentValues);
                    $this->assertNotFalse($idx);
                    $this->assertEquals($expectedHeader, $userAgentValues[$idx]);

                    return new Response;
                }
            ]);
            $s3->listBuckets();
        } finally {
            $deferTask();
        }
    }

    public function testResolvesAppIdSourcedFromIniFile()
    {
        $tempIniConfigFile = sys_get_temp_dir() . '/.aws/config';
        $currentAwsConfigFileFromEnv = getenv('AWS_CONFIG_FILE');
        $deferTask = function () use ($tempIniConfigFile, $currentAwsConfigFileFromEnv) {
            unlink($tempIniConfigFile);
            if (!empty($currentAwsConfigFileFromEnv)) {
                putenv("AWS_CONFIG_FILE=$currentAwsConfigFileFromEnv");
            }
        };
        $appId = 'TestAppId';
        $iniContent = <<<EOF
[default]
sdk_ua_app_id=$appId
EOF;
        file_put_contents($tempIniConfigFile, $iniContent);
        putenv("AWS_CONFIG_FILE=$tempIniConfigFile");
        try {
            $s3 = new S3Client([
                'region' => 'us-east-1',
                'http_handler' => function (RequestInterface $request) use ($appId) {
                    $userAgentValues = explode(' ', $request->getHeader('user-agent')[0]);
                    $expectedHeader = "app/$appId";
                    $idx = array_search($expectedHeader, $userAgentValues);
                    $this->assertNotFalse($idx);
                    $this->assertEquals($expectedHeader, $userAgentValues[$idx]);

                    return new Response;
                }
            ]);
            $s3->listBuckets();
        } finally {
            $deferTask();
        }
    }

    public function testAppliesAuthSchemeResolver()
    {
        $r = new ClientResolver(ClientResolver::getDefaultArguments());
        $conf = $r->resolve([
            'service' => 'dynamodb',
            'region' => 'x',
            'version' => 'latest',
        ], new HandlerList());
        $this->assertArrayHasKey('auth_scheme_resolver', $conf);
        $this->assertInstanceOf(
            AuthSchemeResolverInterface::class,
            $conf['auth_scheme_resolver']
        );
    }

    public function testEmitsDeprecationWarning()
    {
        if (PHP_VERSION_ID >= 80100) {
            $this->markTestSkipped();
        }

        putenv('AWS_SUPPRESS_PHP_DEPRECATION_WARNING=false');
        $this->expectDeprecation();
        $this->expectDeprecationMessage('This installation of the SDK is using PHP version');

        $r = new ClientResolver(ClientResolver::getDefaultArguments());

        try {
            $r->resolve(['service' => 'sqs', 'region' => 'x'], new HandlerList());
        } finally {
            putenv('AWS_SUPPRESS_PHP_DEPRECATION_WARNING=true');
        }
    }

    /**
     * Tests the flag `use_aws_shared_config_files` is applied to the method
     * for resolving a default value for a config.
     *
     * @return void
     */
    public function testResolveFromEnvIniUseAwsSharedFiles(): void
    {
        // The config being tested
        $configKey = 'foo-config-key';
        $configValue = 'foo-config-value';
        // Populate config file
        $tempDir = sys_get_temp_dir();
        $awsDir = $tempDir . "/.aws";
        if (!is_dir($awsDir)) {
            mkdir($awsDir, 0777, true);
        }
        $configFile = $awsDir . "/config";
        $configData = <<<EOF
[default]
$configKey=$configValue
EOF;
        file_put_contents($configFile, $configData);
        $currentEnvConfigFile = getenv(ConfigurationResolver::ENV_CONFIG_FILE);
        putenv(ConfigurationResolver::ENV_CONFIG_FILE . "=" . $configFile);

        try {
            $resolver = new ClientResolver([
                $configKey => [
                    'type' => 'value',
                    'valid' => ['string'],
                    'fn' => function ($value, array &$args) use ($configKey) {
                        if (empty($value)) {
                            $args[$configKey] = null;

                            return;
                        }

                        $args[$configKey] = $value;
                    },
                    'default' => ClientResolver::DEFAULT_FROM_ENV_INI
                ]
            ]);
            $testCases = [
                [
                    'args' => [
                        'use_aws_shared_config_files' => true
                    ],
                    'expected' => $configValue
                ],
                [
                    'args' => [
                        'use_aws_shared_config_files' => false
                    ],
                    'expected' => null
                ]
            ];
            foreach ($testCases as $case) {
                $resolvedArgs = $resolver->resolve(
                    $case['args'],
                    new HandlerList()
                );
                if ($case['expected']) {
                    $this->assertEquals(
                        $case['expected'],
                        $resolvedArgs[$configKey]
                    );
                } else {
                    $this->assertNull($resolvedArgs[$configKey]);
                }
            }
        } finally {
            unlink($configFile);
            if ($currentEnvConfigFile) {
                putenv(
                    ConfigurationResolver::ENV_CONFIG_FILE
                    . "="
                    . $currentEnvConfigFile
                );
            } else {
                putenv(ConfigurationResolver::ENV_CONFIG_FILE);
            }
        }
    }
}
