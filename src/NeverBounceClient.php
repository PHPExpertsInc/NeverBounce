<?php declare(strict_types=1);

/**
 * This file is part of a NeverBounce API Client, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019-2025 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *   GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *   https://www.phpexperts.pro/
 *   https://github.com/PHPExpertsInc/NeverBounce
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\NeverBounceClient;

use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\NeverBounceClient\DTOs\BulkRequestDTO;
use PHPExperts\NeverBounceClient\DTOs\BulkValidationDTO;
use PHPExperts\NeverBounceClient\DTOs\EmailValidationDTO;
use PHPExperts\RESTSpeaker\RESTSpeaker;

// Note to developer: This class must be compatible with PHP 7.4-8.4.
class NeverBounceClient
{
    /** @var RESTSpeaker */
    protected $api;

    /** @var BulkValidationDTO|object|null */
    private $lastResponse;

    /** @var string */
    private $lastJobStatus = '';

    private function __construct(RESTSpeaker $client)
    {
        $this->api = $client;
    }

    public static function build(RESTSpeaker $client = null): self
    {
        // @TODO: Implement the code here.

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

    public function validate(string $email): EmailValidationDTO
    {
        // @TODO: Implement
    }

    public function isValid(string $email): bool
    {
        // @TODO: Implement
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
        // @TODO: Implement
    }

    /**
     * See https://developers.neverbounce.com/docs/verifying-a-list.
     *
     * @param int $jobId
     *
     * @return BulkValidationDTO|null array if the job has finished; null if it is still being processed
     */
    public function checkJob(int $jobId): ?BulkValidationDTO
    {
        // @TODO: Implement
    }
}
