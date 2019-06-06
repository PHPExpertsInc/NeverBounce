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

namespace PHPExperts\NeverBounceClient\DTOs;

use Carbon\Carbon;
use PHPExperts\SimpleDTO\NestedDTO;

/**
 * @see https://developers.neverbounce.com/docs/verifying-a-list#section-2-checking-the-status
 *
 * @property-read string       $status
 * @property-read int          $id
 * @property-read string       $filename
 * @property-read Carbon       $created
 * @property-read ?Carbon      $started
 * @property-read ?Carbon      $finished
 * @property-read ListStatsDTO $total
 * @property-read float        $bounce_estimate
 * @property-read float        $percent_complete
 * @property-read string       $job_status
 * @property-read int          $execution_time
 */
final class BulkValidationDTO extends NestedDTO
{
    public function __construct(array $input)
    {
        $DTOs = [
            'total' => ListStatsDTO::class,
        ];

        parent::__construct($input, $DTOs, [NestedDTO::PERMISSIVE]);
    }
}
