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

namespace PHPExperts\NeverBounceClient\internal;

/**
 * @internal
 */
class EmailFlags
{
    /** @see https://developers.neverbounce.com/reference#section-flags */

    /** @var string The input has one or more DNS records associated with the hostname. */
    public const HAS_DNS = 'has_dns';

    /** @var string The input has mail exchanger DNS records configured. */
    public const HAS_DNS_MX = 'has_dns_mx';

    /** @var string The input given doesn't appear to be an email. */
    public const BAD_SYNTAX = 'bad_syntax';

    /** @var string This email is registered on a free-mail host. (e.g: yahoo.com, hotmail.com) */
    public const FREE_HOST = 'free_email_host';

    /** @var string The email appears to have a dirty word in it. */
    public const PROFANITY = 'profanity';

    /** @var string This email is a role-based email address (e.g: admin@, help@, sales@) */
    public const ROLE_ACCOUNT = 'role_account';

    /** @var string The input given is a disposable email. */
    public const DISPOSABLE = 'disposable_email';

    /** @var string The input given is a government email. */
    public const GOVERNMENT = 'government_host';

    /** @var string The input given is a acedemic email. */
    public const ACADEMIC = 'academic_host';

    /** @var string The input given is a military email. */
    public const MILITARY = 'military_host';

    /** @var string INT designated domain names. */
    public const INTERNATIONAL = 'international_host';

    /** @var string Host likely intended to look like a big-time provider (type of spam trap). */
    public const SQUATTER_HOST = 'squatter_host';

    /** @var string The domain either doesn't exist or there is no email MX record. */
    public const BAD_DNS = 'bad_dns';

    /** @var string There is a DNS issue that should resolve shortly. */
    public const TEMP_DNS_ERROR = 'temporary_dns_error';

    /** @var string Unable to connect to remote host. */
    public const CONNECTION_FAILED = 'connect_fails';

    /** @var string The remote host accepts mail at any address. */
    public const ACCEPTS_ALL = 'accepts_all';

    /** @var string The email address supplied contains an address part and an alias part. */
    public const CONTAINS_ALIAS = 'contains_alias';

    /** @var string The host in the address contains a subdomain. */
    public const CONTAINS_SUBDOMAIN = 'contains_subdomain';

    /** @var string NeverBounce was able to connect to the remote mail server. */
    public const WORKING_SMTP = 'smtp_connectable';

    /** @var string The host is affiliated with a known spam trap network. */
    public const SPAMTRAP_NETWORK = 'spamtrap_network';

    public const ALL_FLAGS = [
        self::HAS_DNS,            /*  1 */
        self::HAS_DNS_MX,         /*  2 */
        self::BAD_SYNTAX,         /*  3 */
        self::FREE_HOST,          /*  4 */
        self::PROFANITY,          /*  5 */
        self::ROLE_ACCOUNT,       /*  6 */
        self::DISPOSABLE,         /*  7 */
        self::GOVERNMENT,         /*  8 */
        self::ACADEMIC,           /*  9 */
        self::MILITARY,           /* 10 */
        self::INTERNATIONAL,      /* 11 */
        self::SQUATTER_HOST,      /* 11 */
        self::BAD_DNS,            /* 12 */
        self::TEMP_DNS_ERROR,     /* 13 */
        self::CONNECTION_FAILED,  /* 14 */
        self::ACCEPTS_ALL,        /* 15 */
        self::CONTAINS_ALIAS,     /* 16 */
        self::CONTAINS_SUBDOMAIN, /* 17 */
        self::WORKING_SMTP,       /* 18 */
        self::SPAMTRAP_NETWORK,   /* 19 */
    ];

    public const MISC_FLAGS = [
        self::GOVERNMENT,
        self::MILITARY,
        self::ACADEMIC,
        self::INTERNATIONAL,
        self::CONTAINS_ALIAS,
        self::CONTAINS_SUBDOMAIN,
    ];

    public const GOOD_FLAGS = [
        self::HAS_DNS,
        self::HAS_DNS_MX,
        self::WORKING_SMTP,
    ];

    public const NOT_GOOD_FLAGS = [
        self::FREE_HOST,
        self::ROLE_ACCOUNT,
        self::DISPOSABLE,
        self::ACCEPTS_ALL,
    ];

    public const BAD_FLAGS = [
        self::BAD_SYNTAX,
        self::BAD_DNS,
        self::TEMP_DNS_ERROR,
        self::CONNECTION_FAILED,
        self::PROFANITY,
    ];

    public const TERRIBLE_FLAGS = [
        self::SQUATTER_HOST,
        self::SPAMTRAP_NETWORK,
    ];

    public static function isValid(string $flag): bool
    {
        return in_array($flag, static::ALL_FLAGS, true);
    }
}
