<?php

namespace PHPExperts\NeverBounceClient;

use Throwable;

class NeverBounceAPIException extends \RuntimeException
{
    private $lastResponse;

    public function __construct($lastResponse, string $message = '', int $code = 0, Throwable $previous = null)
    {
        $this->lastResponse = $lastResponse;

        if ($message === '') {
            $message = 'The API returned a mal-formed response. See $e->getResponse().';
        }

        parent::__construct($message, $code, $previous);
    }

    public function getResponse()
    {
        return $this->lastResponse;
    }
}