<?php

namespace Monitor\Helpers;

use Monitor\Helpers\ResponseFromService;

final class RequestToService
{
    private $ch;

    /**
     * RequestToService constructor.
     *
     * @param $url
     */
    public function __construct($url)
    {
        $this->ch = curl_init($url);

        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($this->ch, CURLOPT_TIMEOUT,5);
    }

    /**
     * Send request to an url.
     *
     * @return \Monitor\Helpers\ResponseFromService
     */
    public function send() {
        $execResult = curl_exec($this->ch);

        if (!$execResult) {
            $curlError = curl_error($this->ch);
            throw new \Exception("Curl execution failed: $curlError");
        }

        return new ResponseFromService($execResult, $this->ch);
    }
}