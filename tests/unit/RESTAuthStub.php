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

/**
 * This file is from the RESTSpeaker, a PHP Experts, Inc., Project.
 *   https://github.com/PHPExpertsInc/RESTSpeaker.
 */

namespace PHPExperts\NeverBounceClient\Tests\Unit;

use PHPExperts\RESTSpeaker\RESTAuth;

class RESTAuthStub extends RESTAuth
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        $this->authMode = RESTAuth::AUTH_NONE;
    }

    protected function generateOAuth2TokenOptions(): array
    {
        return [];
    }

    /**
     * @return array the appropriate headers for passkey authorization
     */
    protected function generatePasskeyOptions(): array
    {
        return [];
    }
}
