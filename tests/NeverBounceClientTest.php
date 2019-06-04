<?php declare(strict_types=1);

/**
 * This file is part of a NeverBounce API Client, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *   GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *   https://www.phpexperts.pro/
 *   https://github.com/phpexpertsinc/NeverBounce
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
}
