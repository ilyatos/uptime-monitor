<?php

namespace Monitor\Exceptions;

use Throwable;

class CurlExecutionException extends \Exception
{
    /**
     * CurlExecutionException constructor.
     *
     * @param string $url
     * @param string $reason
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $url, string $reason = '', int $code = 0, Throwable $previous = null)
    {
        $message = "Curl execution failed for URL - $url. Because: $reason";
        parent::__construct($message, $code, $previous);
    }

    /**
     * Converts CurlExecutionException to string format.
     *
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__ . ": $this->message" . PHP_EOL;
    }
}
