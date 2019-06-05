<?php declare(strict_types=1);

/**
 * This file is part of RESTSpeaker, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *   GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *   https://www.phpexperts.pro/
 *   https://github.com/phpexpertsinc/RESTSpeaker
 *
 * This file is licensed under the MIT License.
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
     * @return array The appropriate headers for passkey authorization.
     */
    protected function generatePasskeyOptions(): array
    {
        return [];
    }
}
