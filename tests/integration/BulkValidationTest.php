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

namespace PHPExperts\NeverBounceClient\Tests;

use PHPExperts\NeverBounceClient\NeverBounceClient;

/** @testdox PHPExperts\NeverBounceClient: Bulk Validations */
class BulkValidationTest extends TestCase
{
    public static function getVariousEmails(): array
    {
        return [
            'valid'           => 'support@neverbounce.com',
            'valid_free'      => 'hopeseekr@gmail.com',
            'catchall'        => 'sales@phpexperts.pro',
            'invalid_domain'  => 'hopefully@thisdomainwillnever.exist',
            'invalid_account' => 'hopefully-doesnt-exist@gmail.com',
            'duplicate'       => 'hopeseekr@gmail.com',
            'bad_syntax'      => 'terrible$#@#syntax!',
        ];
    }

    public function testCanSubmitABulkValidationRequest(): int
    {
        $client = NeverBounceClient::build();

        $jobId = $client->bulkVerify($this->getVariousEmails());
        self::assertIsInt($jobId);

        $response = $client->getLastResponse();
        self::assertEquals('success', $response->status);
        self::assertIsInt($response->job_id);

        return $jobId;
    }

    /**
     * @depends testCanSubmitABulkValidationRequest
     *
     * @return array
     */
    public function testCanPollJobUntilCompleted(int $jobId): array
    {
        $client = NeverBounceClient::build();

        $response = null;
        // Try for up to 5 minutes.
        for ($a = 1; $a <= 300; ++$a) {
            $response = $client->checkJob($jobId);
            self::assertEquals($client->getLastJobStatus(), $client->getLastResponse()->job_status);

            if ($response === null) {
                if (TestCase::isDebugOn()) {
                    dump('Job is not done: ' . $client->getLastJobStatus());
                }

                self::assertNotContains(
                    $client->getLastJobStatus(),
                    ['failed', 'under_review', 'complete'],
                    'The bulk validation job returned an unexpected status.'
                );

                $seconds = (int) ceil($a / 5);
                if (TestCase::isDebugOn()) {
                    dump("Sleeping for $seconds seconds...");
                }

                sleep($seconds);
            }

            if (is_array($response)) {
                break;
            }
        }

        self::assertIsArray($response);
        self::assertNotEmpty($response);

        return $response;
    }

    /**
     * @depends testCanPollJobUntilCompleted
     *
     * @param array $response
     */
    public function testWillRetrieveBulkValidationResults(array $response)
    {
        if (TestCase::isDebugOn()) {
            dump($response);
        }

        self::assertNotEmpty($response);
        self::assertArrayHasKey('status', $response);
        self::assertEquals('success', $response['status']);
        self::assertNotEmpty($response['total']);
        $response['total'] = (array) $response['total'];
        self::assertNotEmpty($response['bounce_estimate']);
        self::assertNotEmpty($response['percent_complete']);
        self::assertNotEmpty($response['job_status']);

        $expected = [
            'records'    => 7,
            'billable'   => 5,
            'processed'  => 7,
            'valid'      => 3,
            'invalid'    => 3,
            'catchall'   => 1,
            'disposable' => 0,
            'unknown'    => 0,
            'duplicates' => 1,
            'bad_syntax' => 1,
        ];

        self::assertEquals('complete', $response['job_status']);
        self::assertSame($expected, $response['total']);
    }
}
