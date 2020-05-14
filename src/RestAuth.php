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

namespace PHPExperts\NeverBounceClient;

use LogicException;
use PHPExperts\RESTSpeaker\RESTAuth as BaseAuth;

class RestAuth extends BaseAuth
{
    protected function generateOAuth2TokenOptions(): array
    {
        throw new LogicException('NeverBounce no longer supports OAuth2.');
    }

    protected function generatePasskeyOptions(): array
    {
        return [
            'query' => ['key' => env('NEVERBOUNCE_API_KEY')],
        ];
    }
}
