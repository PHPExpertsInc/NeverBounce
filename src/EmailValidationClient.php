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

use PHPExperts\NeverBounceClient\DTOs\BulkValidationDTO;
use PHPExperts\NeverBounceClient\DTOs\EmailValidationDTO;

interface EmailValidationClient
{
    public function getLastResponse();
    public function getLastJobStatus(): string;
    public function validate(string $email): EmailValidationDTO;
    public function isValid(string $email): bool;

    /**
     * Bulk validates an array of emails.
     *
     * @param string[] $emails
     *
     * @return int the jobId that's needed for further processing
     */
    public function bulkVerify(array $emails): int;

    /**
     * See https://developers.neverbounce.com/docs/verifying-a-list.
     *
     * @param int $jobId
     *
     * @return BulkValidationDTO|null array if the job has finished; null if it is still being processed
     */
    public function checkJob(int $jobId): ?BulkValidationDTO;
}
