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

namespace PHPExperts\NeverBounceClient\DTOs;

use PHPExperts\SimpleDTO\SimpleDTO;

/**
 * @see https://developers.neverbounce.com/docs/verifying-a-list#section-2-checking-the-status
 *
 * @property-read int $records
 * @property-read int $billable
 * @property-read int $processed
 * @property-read int $valid
 * @property-read int $invalid
 * @property-read int $catchall
 * @property-read int $disposable
 * @property-read int $unknown
 * @property-read int $duplicates
 * @property-read int $bad_syntax
 */
final class ListStatsDTO extends SimpleDTO
{
    public function __construct(array $input)
    {
        parent::__construct($input, [SimpleDTO::PERMISSIVE]);
    }
}
