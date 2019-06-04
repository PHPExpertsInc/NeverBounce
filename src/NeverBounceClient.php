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

namespace PHPExperts\NeverBounceClient;

use http\Exception\RuntimeException;
use PHPExperts\RESTSpeaker\RESTAuthDriver;
use PHPExperts\RESTSpeaker\RESTSpeaker;

class NeverBounceClient
{
    /** @var RESTSpeaker */
    protected $api;

    private function __construct(RESTSpeaker $client)
    {
        $this->api = $client;
    }

    public static function build(RESTSpeaker $client = null, RESTAuthDriver $restAuth = null): self
    {
        if ($restAuth === null) {
            $restAuth = new RestAuth(RestAuth::AUTH_MODE_PASSKEY);
        }

        if ($client === null) {
            $client = new RESTSpeaker($restAuth, 'https://api.neverbounce.com');
        }

        return new self($client);
    }

    public function validate(string $email): \stdClass
    {
        $response = $this->api->post('/v4/single/check', [
            'json' => ['email' => $email],
        ]);

        if (!$response || !($response instanceof \stdClass) || ($response->status ?? false) !== 'success') {
            throw new NeverBounceAPIException($this->api->getLastResponse(), '', $this->api->getLastStatusCode());
        }

        return $response;
    }

    public function isValid(string $email): bool
    {
        $response = $this->validate($email);

        if (!$response || !($response instanceof \stdClass) || ($response->status ?? false) !== 'success') {
            throw new NeverBounceAPIException($this->api->getLastResponse(), '', $this->api->getLastStatusCode());
        }

        $status = $response->result ?? false;

        return $status === 'valid' || $status === 'catchall';
    }
}
