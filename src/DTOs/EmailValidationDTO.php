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

use PHPExperts\NeverBounceClient\internal\EmailFlags;
use PHPExperts\SimpleDTO\SimpleDTO;

/**
 * @see https://developers.neverbounce.com/docs/verifying-an-email
 *
 * @property-read string   $status
 * @property-read string   $result
 * @property-read string[] $flags
 * @property-read string   $suggested_correction
 * @property-read int      $execution_time
 */
final class EmailValidationDTO extends SimpleDTO
{
    /** @var string Verified as a real address. */
    public const RESULT_VALID = 'valid';

    /** @var string Verified as an invalid address. */
    public const RESULT_INVALID = 'invalid';

    /** @var string A temporary, disposable address. */
    public const RESULT_DISPOSABLE = 'disposable';

    /** @var string A domain-wide email (Uusually unverifiable). */
    public const RESULT_CATCHALL = 'catchall';

    /** @var string The server cannot be reached. */
    public const RESULT_UNKNOWN = 'unknown';

    public const RESULTS = [
        self::RESULT_VALID,
        self::RESULT_INVALID,
        self::RESULT_DISPOSABLE,
        self::RESULT_CATCHALL,
        self::RESULT_UNKNOWN,
    ];

    protected function extraValidation(array $input)
    {
        if (!in_array($input['result'], self::RESULTS, true)) {
            throw new \InvalidArgumentException("Invalid result: '{$input['result']}'.");
        }

        // Ensure that all of the flags are valid.
        foreach ($input['flags'] as $flag) {
            if (EmailFlags::isValid($flag) === false) {
                throw new \InvalidArgumentException("Invalid email verification flag: '$flag'.");
            }
        }
    }
}
