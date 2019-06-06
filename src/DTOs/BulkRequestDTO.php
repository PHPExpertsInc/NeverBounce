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

use PHPExperts\SimpleDTO\SimpleDTO;
use PHPExperts\SimpleDTO\WriteOnce;

/**
 * @see https://developers.neverbounce.com/docs/verifying-a-list
 *
 * @property string $input_location
 * @property string $filename
 * @property bool   $auto_start
 * @property bool   $auto_parse
 * @property array  $input
 */
final class BulkRequestDTO extends SimpleDTO
{
    use WriteOnce;
}
