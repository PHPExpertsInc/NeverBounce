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

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Constructs a test case with the given name.
     *
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $dotenv = Dotenv::create(__DIR__ . '/../', '.env');
        $dotenv->load();

        parent::__construct($name, $data, $dataName);
    }
}
