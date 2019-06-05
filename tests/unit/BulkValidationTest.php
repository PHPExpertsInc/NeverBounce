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

namespace PHPExperts\NeverBounceClient\Tests\Unit;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler as GuzzleMocker;
use GuzzleHttp\Psr7\Response;
use PHPExperts\NeverBounceClient\NeverBounceClient;
use PHPExperts\NeverBounceClient\Tests\Integration\BulkValidationTest as BulkValidationIntegrationTestCase;
use PHPExperts\RESTSpeaker\HTTPSpeaker;
use PHPExperts\RESTSpeaker\RESTSpeaker;

/** @testdox PHPExperts\NeverBounceClient: Bulk Validations Unit Tests */
class BulkValidationTest extends BulkValidationIntegrationTestCase
{
    /** @var GuzzleMocker */
    private $guzzlePuppet;

    public function setUp(): void
    {
        $restAuthStub = new RESTAuthStub();
        $this->guzzlePuppet = new GuzzleMocker();
        $http = new HTTPSpeaker('', new GuzzleClient(['handler' => $this->guzzlePuppet]));

        $restSpeaker = new RESTSpeaker($restAuthStub, '', $http);
        $this->api = NeverBounceClient::build($restSpeaker);

        parent::setUp();
    }

    protected function craftGuzzleResponse(array $input, int $statusCode = 200)
    {
        $this->guzzlePuppet->append(
            new Response(
                $statusCode,
                ['Content-Type' => 'application/json'],
                json_encode($input)
            )
        );
    }

    public function testCanSubmitABulkValidationRequest(): int
    {
        $this->craftGuzzleResponse([
            'status'         => 'success',
            'job_id'         => 2917456,
            'execution_time' => 1624,
        ]);

        return parent::testCanSubmitABulkValidationRequest();
    }

    public function testCanPollJobUntilCompleted(int $jobId = 2917483): array
    {
        $this->craftGuzzleResponse([
            'status'           => 'success',
            'id'               => $jobId,
            'job_status'       => 'pending',
            'filename'         => 'bulk-1559703280.csv',
            'created_at'       => '2019-06-04 22:54:41',
            'started_at'       => null,
            'finished_at'      => null,
            'total'            => (object) [
                'records'    => 0,
                'billable'   => 0,
                'processed'  => 0,
                'valid'      => 0,
                'invalid'    => 0,
                'catchall'   => 0,
                'disposable' => 0,
                'unknown'    => 0,
                'duplicates' => 0,
                'bad_syntax' => 0,
            ],
            'bounce_estimate'  => 0,
            'percent_complete' => 0,
            'execution_time'   => 0,
        ]);

        $this->craftGuzzleResponse([
            'status'      => 'success',
            'id'          => $jobId,
            'job_status'  => 'parsing',
            'filename'    => 'bulk-1559703280.csv',
            'created_at'  => '2019-06-04 22:54:41',
            'started_at'  => null,
            'finished_at' => null,
            'total'       => (object) [
                'records'    => 0,
                'billable'   => 0,
                'processed'  => 0,
                'valid'      => 0,
                'invalid'    => 0,
                'catchall'   => 0,
                'disposable' => 0,
                'unknown'    => 0,
                'duplicates' => 0,
                'bad_syntax' => 0,
            ],
            'bounce_estimate'  => 0,
            'percent_complete' => 0,
            'execution_time'   => 0,
        ]);

        $this->craftGuzzleResponse([
            'status'           => 'success',
            'id'               => 2917483,
            'job_status'       => 'complete',
            'filename'         => 'bulk-1559703280.csv',
            'created_at'       => '2019-06-04 22:54:41',
            'started_at'       => date('Y-m-d h:i:s'),
            'finished_at'      => date('Y-m-d h:i:s'),
            'total'            => (object) [
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
            ],
            'bounce_estimate'  => 28.571428571429,
            'percent_complete' => 100,
            'execution_time'   => 12,
        ]);

        return parent::testCanPollJobUntilCompleted($jobId);
    }

    public function testWillRetrieveBulkValidationResults(array $response = null)
    {
        $response = [
            'status'           => 'success',
            'id'               => 2917483,
            'job_status'       => 'complete',
            'filename'         => 'bulk-1559703280.csv',
            'created_at'       => '2019-06-04 22:54:41',
            'started_at'       => date('Y-m-d h:i:s'),
            'finished_at'      => date('Y-m-d h:i:s'),
            'total'            => (object) [
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
            ],
            'bounce_estimate'  => 28.571428571429,
            'percent_complete' => 100,
            'execution_time'   => 12,
        ];

        parent::testWillRetrieveBulkValidationResults($response);
    }
}
