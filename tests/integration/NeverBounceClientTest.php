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

namespace PHPExperts\NeverBounceClient\Tests;

use PHPExperts\NeverBounceClient\NeverBounceClient;

/** @testdox PHPExperts\NeverBounceClient */
class NeverBounceClientTest extends TestCase
{
    public function testCanBuildItself()
    {
        $client = NeverBounceClient::build();
        self::assertInstanceOf(NeverBounceClient::class, $client);
    }

    public function testWillValidateAGoodEmail()
    {
        $client   = NeverBounceClient::build();
        $response = $client->validate('support@neverbounce.com');

        self::assertInstanceOf(\stdClass::class, $response);
        self::assertEquals('success', $response->status);
        self::assertEquals('valid', $response->result);
        self::assertEquals('', $response->suggested_correction);
    }

    public function testWillValidateACatchAllEmail()
    {
        $client   = NeverBounceClient::build();
        $response = $client->validate('catchall@phpexperts.pro');

        self::assertInstanceOf(\stdClass::class, $response);
        self::assertEquals('success', $response->status);
        self::assertEquals('catchall', $response->result);
        self::assertEquals('', $response->suggested_correction);
    }

    public function testWillValidateAnInvalidDomainEmail()
    {
        $client   = NeverBounceClient::build();
        $response = $client->validate('hopefully@thisdomainwillnever.exist');
//        dd($response);

        self::assertInstanceOf(\stdClass::class, $response);
        self::assertEquals('success', $response->status);
        self::assertEquals('invalid', $response->result);
        self::assertEquals('', $response->suggested_correction);
        self::assertContains('bad_dns', $response->flags);
    }

    public function testWillValidateAnInvalidAccountEmail()
    {
        $client   = NeverBounceClient::build();
        $response = $client->validate('hopefully-doesnt-exist@gmail.com');

        self::assertInstanceOf(\stdClass::class, $response);
        self::assertEquals('success', $response->status);
        self::assertEquals('invalid', $response->result);
        self::assertEquals('', $response->suggested_correction);
        self::assertContains('has_dns', $response->flags);
    }

    public function testWillDetectFreeEmailHosts()
    {
        $client   = NeverBounceClient::build();
        $response = $client->validate('hopeseekr@gmail.com');

        self::assertInstanceOf(\stdClass::class, $response);
        self::assertEquals('success', $response->status);
        self::assertEquals('valid', $response->result);
        self::assertEquals('', $response->suggested_correction);
        self::assertContains('has_dns', $response->flags);
        self::assertContains('free_email_host', $response->flags);
    }

    public function testCanDetermineIfAnEmailIsGood()
    {
        $client   = NeverBounceClient::build();
        $response = $client->isValid('theodore@phpexperts.pro');

        self::assertTrue($response, 'A valid email returned false.');
    }

    public function testCanDetermineIfAnEmailHasAnInvalidDomain()
    {
        $client   = NeverBounceClient::build();
        $response = $client->isValid('hopefully@thisdomainwillnever.exist');

        self::assertFalse($response, 'An email with an invalid domain returned true.');
    }

    public function testCanDetermineIfAnEmailHasAnInvalidAccount()
    {
        $client   = NeverBounceClient::build();
        $response = $client->isValid('hopefully-doesnt-exist@gmail.com');

        self::assertFalse($response, 'A valid email with an invalid account returned true.');
    }
}
