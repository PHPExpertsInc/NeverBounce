<?php declare(strict_types=1);

/**
 * This file is part of a NeverBounce API Client, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019-2020 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *   GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *   https://www.phpexperts.pro/
 *   https://github.com/PHPExpertsInc/NeverBounce
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\NeverBounceClient\Tests\Integration;

use PHPExperts\NeverBounceClient\DTOs\EmailValidationDTO;
use PHPExperts\NeverBounceClient\NeverBounceClient;
use PHPExperts\NeverBounceClient\Tests\TestCase;

/**
 * @testdox PHPExperts\NeverBounceClient
 */
class NeverBounceClientTest extends TestCase
{
    /** @var NeverBounceClient */
    protected $api;

    public function setUp(): void
    {
        if ($this->api === null) {
            $this->api = NeverBounceClient::build();
        }

        parent::setUp();
    }

    /** @group thorough */
    public function testWillValidateAGoodEmail()
    {
        $response = $this->api->validate('support@neverbounce.com');

        self::assertInstanceOf(EmailValidationDTO::class, $response);
        self::assertEquals('success', $response->status);
        self::assertEquals('valid', $response->result);
        self::assertEquals('', $response->suggested_correction);
    }

    /** @group thorough */
    public function testWillValidateACatchAllEmail()
    {
        $response = $this->api->validate('catchall@phpexperts.pro');

        self::assertInstanceOf(EmailValidationDTO::class, $response);
        self::assertEquals('success', $response->status);
        self::assertEquals('catchall', $response->result);
        self::assertEquals('', $response->suggested_correction);
    }

    /** @group thorough */
    public function testWillValidateAnInvalidDomainEmail()
    {
        $response = $this->api->validate('hopefully@thisdomainwillnever.exist');

        self::assertInstanceOf(EmailValidationDTO::class, $response);
        self::assertEquals('success', $response->status);
        self::assertEquals('invalid', $response->result);
        self::assertEquals('', $response->suggested_correction);
        self::assertContains('bad_dns', $response->flags);
    }

    /** @group thorough */
    public function testWillValidateAnInvalidAccountEmail()
    {
        $response = $this->api->validate('hopefully-doesnt-exist@gmail.com');

        self::assertInstanceOf(EmailValidationDTO::class, $response);
        self::assertEquals('success', $response->status);
        self::assertEquals('invalid', $response->result);
        self::assertEquals('', $response->suggested_correction);
        self::assertContains('has_dns', $response->flags);
    }

    /** @group thorough */
    public function testWillDetectFreeEmailHosts()
    {
        $response = $this->api->validate('hopeseekr@gmail.com');

        self::assertInstanceOf(EmailValidationDTO::class, $response);
        self::assertEquals('success', $response->status);
        self::assertEquals('valid', $response->result);
        self::assertEquals('', $response->suggested_correction);
        self::assertContains('has_dns', $response->flags);
        self::assertContains('free_email_host', $response->flags);
    }

    /** @group thorough */
    public function testCanDetermineIfAnEmailIsGood()
    {
        $response = $this->api->isValid('theodore@phpexperts.pro');

        self::assertTrue($response, 'A valid email returned false.');
    }

    /** @group thorough */
    public function testCanDetermineIfAnEmailHasAnInvalidDomain()
    {
        $response = $this->api->isValid('hopefully@thisdomainwillnever.exist');

        self::assertFalse($response, 'An email with an invalid domain returned true.');
    }

    /** @group thorough */
    public function testCanDetermineIfAnEmailHasAnInvalidAccount()
    {
        $response = $this->api->isValid('hopefully-doesnt-exist@gmail.com');

        self::assertFalse($response, 'A valid email with an invalid account returned true.');
    }
}
