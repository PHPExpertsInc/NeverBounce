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

use PHPExperts\RESTSpeaker\RESTSpeaker;

class NeverBounceClient
{
    /** @var RESTSpeaker */
    protected $api;

    /** @var \stdClass|object|null */
    private $lastResponse;

    /** @var string */
    private $lastJobStatus = '';

    private function __construct(RESTSpeaker $client)
    {
        $this->api = $client;
    }

    public static function build(RESTSpeaker $client = null): self
    {
        if ($client === null) {
            $restAuth = new RestAuth(RestAuth::AUTH_MODE_PASSKEY);
            $client = new RESTSpeaker($restAuth, 'https://api.neverbounce.com');
        }

        return new self($client);
    }

    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function getLastJobStatus(): string
    {
        return $this->lastJobStatus;
    }

    public function validate(string $email): \stdClass
    {
        $response = $this->api->post('/v4/single/check', [
            'json' => ['email' => $email],
        ]);

        $this->lastResponse = $response;

        if (!$response || !($response instanceof \stdClass) || ($response->status ?? false) !== 'success') {
            throw new NeverBounceAPIException($this->api->getLastResponse(), '', $this->api->getLastStatusCode());
        }

        return $response;
    }

    public function isValid(string $email): bool
    {
        $response = $this->validate($email);

        $this->lastResponse = $response;

        if (!$response || !($response instanceof \stdClass) || ($response->status ?? false) !== 'success') {
            throw new NeverBounceAPIException($this->api->getLastResponse(), '', $this->api->getLastStatusCode());
        }

        $status = $response->result ?? false;

        return $status === 'valid' || $status === 'catchall';
    }

    /**
     * Bulk validates an array of emails.
     *
     * @param string[] $emails
     *
     * @return int the jobId that's needed for further processing
     */
    public function bulkVerify(array $emails): int
    {
        $payload = [];
        foreach ($emails as $index => $email) {
            if (!is_string($email) || strpos($email, '@') === false) {
                if (!in_array(gettype($email), ['string', 'int', 'float'])) {
                    $email = gettype($email);
                }
                throw new InvalidEmailAddressException("The email at index $index is obviously invalid: '$email'.");
            }

            $payload[] = [$email];
        }

        $epoch = time();

        $response = $this->api->post('/v4/jobs/create', [
            'json' => [
                'input_location' => 'supplied',
                'filename'       => "bulk-$epoch.csv",
                'auto_start'     => true,
                'auto_parse'     => true,
                'input'          => $payload,
            ],
        ]);

        $this->lastResponse = $response;

        if (($response->status ?? false) !== 'success' || !is_int($response->job_id ?? null)) {
            throw new NeverBounceAPIException($response, 'Bulk validation failed. See last response.');
        }

        $jobId = $response->job_id;

        return $jobId;
    }

    /**
     * See https://developers.neverbounce.com/docs/verifying-a-list.
     *
     * @param int $jobId
     *
     * @return array|null array if the job has finished; null if it is still being processed
     */
    public function checkJob(int $jobId): ?array
    {
        $response = $this->api->post('/v4/jobs/status', [
            'json' => [
                'job_id' => $jobId,
            ],
        ]);

        if (($response->status ?? false) !== 'success' || !is_string($response->job_status ?? null)) {
            throw new NeverBounceAPIException($response, 'Bulk validation check failed. See last response.');
        }

        $this->lastResponse = $response;
        $this->lastJobStatus = $response->job_status;

        if (in_array($response->job_status, ['failed', 'under_review'])) {
            throw new NeverBounceAPIException($response, 'A Bulk validation job has failed: ' . $response->job_status);
        }

        if ($response->job_status !== 'complete') {
            return null;
        }

        return (array) $response;
    }
}
