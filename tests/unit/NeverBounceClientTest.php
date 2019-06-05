<?php declare(strict_types=1);

/**
 * This file is part of a NeverBounce API Client, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *   GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *   https://www.phpexperts.pro/
 *   https://github.com/PHPExpertsInc/NeverBounce
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\NeverBounceClient\Tests\Unit;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler as GuzzleMocker;
use GuzzleHttp\Psr7\Response;
use PHPExperts\NeverBounceClient\NeverBounceClient;
use PHPExperts\NeverBounceClient\Tests\Integration\NeverBounceClientTest as NeverBounceIntegrationTestCase;
use PHPExperts\RESTSpeaker\HTTPSpeaker;
use PHPExperts\RESTSpeaker\RESTSpeaker;

/** @testdox PHPExperts\NeverBounceClient Unit Tests */
class NeverBounceClientTest extends NeverBounceIntegrationTestCase
{
    /** @var GuzzleMocker */
    private $guzzlePuppet;

    public function setUp(): void
    {
        $restAuthStub = new RESTAuthStub();
        $this->guzzlePuppet = new GuzzleMocker();
        $http = new HTTPSpeaker('', new GuzzleClient(['handler' => $this->guzzlePuppet]));

        $restSpeaker = new RESTSpeaker($restAuthStub, '', $http);
        $this->api = NeverBounceClient::build($restSpeaker);

        parent::setUp();
    }

    protected function craftGuzzleResponse(array $input, int $statusCode = 200)
    {
        $this->guzzlePuppet->append(
            new Response(
                $statusCode,
                ['Content-Type' => 'application/json'],
                json_encode($input)
            )
        );
    }

    public function testCanBuildItself()
    {
        $client = NeverBounceClient::build();
        self::assertInstanceOf(NeverBounceClient::class, $client);
    }

    public function testWillValidateAGoodEmail(): array
    {
        $expected = [
            'status'               => 'success',
            'result'               => 'valid',
            'suggested_correction' => '',
        ];

        $json = json_encode($expected);
        $this->craftGuzzleResponse($expected);

        parent::testWillValidateAGoodEmail();

        return [$this->api, $expected];
    }

    public function testWillValidateACatchAllEmail()
    {
        $this->craftGuzzleResponse([
            'status'               => 'success',
            'result'               => 'catchall',
            'suggested_correction' => '',
        ]);

        parent::testWillValidateACatchAllEmail();
    }

    public function testWillValidateAnInvalidDomainEmail()
    {
        $this->craftGuzzleResponse([
            'status'               => 'success',
            'result'               => 'invalid',
            'suggested_correction' => '',
            'flags' => [
                'bad_dns',
            ],
        ]);

        parent::testWillValidateAnInvalidDomainEmail();
    }

    public function testWillValidateAnInvalidAccountEmail()
    {
        $this->craftGuzzleResponse([
            'status'               => 'success',
            'result'               => 'invalid',
            'suggested_correction' => '',
            'flags' => [
                'has_dns',
                'free_email_host',
            ],
        ]);

        parent::testCanDetermineIfAnEmailHasAnInvalidAccount();
    }

    public function testWillDetectFreeEmailHosts()
    {
        $this->craftGuzzleResponse([
            'status'               => 'success',
            'result'               => 'valid',
            'suggested_correction' => '',
            'flags' => [
                'has_dns',
                'free_email_host',
            ],
        ]);

        parent::testWillDetectFreeEmailHosts();
    }

    public function testCanDetermineIfAnEmailIsGood()
    {
        $this->craftGuzzleResponse([
            'status' => 'success',
            'result' => 'valid',
        ]);

        $response = $this->api->isValid('hopeseekr@gmail.com');
        self::assertTrue($response);
    }

    public function testCanDetermineIfAnEmailHasAnInvalidDomain()
    {
        $this->craftGuzzleResponse([
            'status' => 'success',
            'result' => 'invalid',
        ]);

        $response = $this->api->isValid('hopefully@thisdomainwillnever.exist');
        self::assertFalse($response);
    }

    public function testCanDetermineIfAnEmailHasAnInvalidAccount()
    {
        $this->craftGuzzleResponse([
            'status' => 'success',
            'result' => 'invalid',
        ]);

        $response = $this->api->isValid('hopefully-doesnt-exist@gmail.com');
        self::assertFalse($response);
    }

    /**
     * @testdox Can get the last API response
     * @depends testWillValidateAGoodEmail
     */
    public function testCanGetTheLastAPIResponse($params)
    {
        [$api, $payload] = $params;
        $expected = (object) $payload;

        self::assertEquals($expected, $api->getLastResponse());
    }
}
