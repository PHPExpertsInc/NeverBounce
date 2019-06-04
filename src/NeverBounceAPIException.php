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

namespace PHPExperts\NeverBounceClient;

use Throwable;

class NeverBounceAPIException extends \RuntimeException
{
    private $lastResponse;

    public function __construct($lastResponse, string $message = '', int $code = 0, Throwable $previous = null)
    {
        $this->lastResponse = $lastResponse;

        if ($message === '') {
            $message = 'The API returned a mal-formed response. See $e->getResponse().';
        }

        parent::__construct($message, $code, $previous);
    }

    public function getResponse()
    {
        return $this->lastResponse;
    }
}
