<?php

namespace Monitor\Helpers;

use Monitor\Exceptions\CurlExecutionException;

final class RequestToService
{
    const DEFAULT_REQUEST_TIMEOUT = 5;

    private static $requestTimeout;

    private $ch;

    /**
     * RequestToService constructor.
     */
    public function __construct()
    {
        self::$requestTimeout = getenv('REQUEST_TIMEOUT') ?: self::DEFAULT_REQUEST_TIMEOUT;
        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, self::$requestTimeout);
    }

    /**
     * Send request to an url.
     *
     * @param string $url
     *
     * @throws CurlExecutionException
     *
     * @return \Monitor\Helpers\ResponseFromService
     */
    public function send(string $url): ResponseFromService
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);

        $execResult = curl_exec($this->ch);

        if (!$execResult) {
            throw new CurlExecutionException($url, curl_error($this->ch));
        }

        return new ResponseFromService($execResult, $this->ch);
    }
}
