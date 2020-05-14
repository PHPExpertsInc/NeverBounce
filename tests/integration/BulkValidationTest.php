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

namespace PHPExperts\NeverBounceClient\Tests\Integration;

use PHPExperts\NeverBounceClient\DTOs\BulkValidationDTO;
use PHPExperts\NeverBounceClient\DTOs\ListStatsDTO;
use PHPExperts\NeverBounceClient\NeverBounceAPIException;
use PHPExperts\NeverBounceClient\NeverBounceClient;
use PHPExperts\NeverBounceClient\Tests\TestCase;

/** @testdox PHPExperts\NeverBounceClient: Bulk Validations */
class BulkValidationTest extends TestCase
{
    /** @var NeverBounceClient */
    protected $api;

    public function setUp(): void
    {
        if ($this->api === null) {
            $this->api = NeverBounceClient::build();
        }

        parent::setUp();
    }

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

    /** @group thorough */
    public function testCanSubmitABulkValidationRequest(): int
    {
        try {
            $jobId = $this->api->bulkVerify($this->getVariousEmails());
        } catch (NeverBounceAPIException $e) {
            dump($e->getResponse());
            throw $e;
            $this->fail('There was an API exception.');
        }
        self::assertIsInt($jobId);

        $response = $this->api->getLastResponse();
        self::assertEquals('success', $response->status);
        self::assertIsInt($response->job_id);

        return $jobId;
    }

    /**
     * @group thorough
     * @depends testCanSubmitABulkValidationRequest
     *
     * @param int $jobId
     *
     * @return BulkValidationDTO
     */
    public function testCanPollJobUntilCompleted(int $jobId): BulkValidationDTO
    {
        $response = null;

        // Try for up to 5 minutes.
        for ($a = 1; $a <= 300; ++$a) {
            try {
                $response = $this->api->checkJob($jobId);
            } catch (NeverBounceAPIException $e) {
                dump([$e->getMessage(), $e->getResponse()]);
                $this->fail('There was an API exception.');
            }
            self::assertEquals($this->api->getLastJobStatus(), $this->api->getLastResponse()->job_status);

            if ($response === null) {
                if (TestCase::isDebugOn()) {
                    dump('Job is not done: ' . $this->api->getLastJobStatus());
                }

                self::assertNotContains(
                    $this->api->getLastJobStatus(),
                    ['failed', 'under_review', 'complete'],
                    'The bulk validation job returned an unexpected status.'
                );

                $seconds = (int) ceil($a / 5);
                if (TestCase::isDebugOn()) {
                    dump("Sleeping for $seconds seconds...");
                }

                sleep($seconds);
            }

            if ($response !== null) {
                break;
            }
        }

        self::assertInstanceOf(BulkValidationDTO::class, $response);
        self::assertEquals('success', $response->status);

        return $response;
    }

    /**
     * @group thorough
     * @depends testCanPollJobUntilCompleted
     *
     * @param BulkValidationDTO $response
     */
    public function testWillRetrieveBulkValidationResults(BulkValidationDTO $response)
    {
        if (TestCase::isDebugOn()) {
            dump($response);
        }

        self::assertInstanceOf(BulkValidationDTO::class, $response);
        self::assertEquals('success', $response->status);
        self::assertInstanceOf(ListStatsDTO::class, $response->total);
        $listStats = $response->total->toArray();
        self::assertNotEmpty($response->bounce_estimate);
        self::assertNotEmpty($response->percent_complete);
        self::assertNotEmpty($response->job_status);

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

        self::assertSame($expected, $listStats);

        self::assertEquals('complete', $response->job_status);
        self::assertGreaterThan(25, $response->bounce_estimate);
        self::assertEquals(100, $response->percent_complete);
    }
}
