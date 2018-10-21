<?php

namespace Monitor\Helpers;

final class RequestToService
{
    private $ch;
    private $executionResult;

    public function __construct($url)
    {
        $this->ch = curl_init($url);

        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($this->ch, CURLOPT_TIMEOUT,5);

        $this->executionResult = $this->executeCh();
    }

    private function executeCh() {
        return curl_exec($this->ch);
    }

    public function getExResult()
    {
        return $this->executionResult;
    }

    public function getResponseHttpCode()
    {
        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }

    public function getResponseTime()
    {
        return curl_getinfo($this->ch, CURLINFO_TOTAL_TIME);
    }

    public function getResponseSize()
    {
        return curl_getinfo($this->ch, CURLINFO_SIZE_DOWNLOAD);
    }


}