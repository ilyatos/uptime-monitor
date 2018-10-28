<?php

namespace Monitor\Helpers;

final class RequestToService
{
    private $ch;

    /**
     * RequestToService constructor.
     */
    public function __construct()
    {
        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 5);
    }

    /**
     * Send request to an url.
     *
     * @param string $url
     * @throws \Exception
     * @return \Monitor\Helpers\ResponseFromService
     */
    public function send(string $url): ResponseFromService
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);

        $execResult = curl_exec($this->ch);

        if (!$execResult) {
            $curlError = curl_error($this->ch);
            throw new \Exception("Curl execution failed: $curlError");
        }

        return new ResponseFromService($execResult, $this->ch);
    }
}
