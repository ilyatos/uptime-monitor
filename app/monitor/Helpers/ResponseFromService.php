<?php


namespace Monitor\Helpers;


class ResponseFromService
{
    private $executionResult;
    private $executedCurl;

    /**
     * ResponseFromService constructor.
     *
     * @param string $executionResult
     * @param $executedCurl
     */
    public function __construct($executionResult, $executedCurl)
    {
        $this->executionResult = $executionResult;
        $this->executedCurl = $executedCurl;
    }

    /**
     * Get response http code.
     *
     * @return int
     */
    public function getHttpCode(): int
    {
        return curl_getinfo($this->executedCurl, CURLINFO_HTTP_CODE);
    }

    /**
     * Get response time.
     *
     * @return float
     */
    public function getTime(): float
    {
        return curl_getinfo($this->executedCurl, CURLINFO_TOTAL_TIME);
    }

    /**
     * Get response size.
     *
     * @return int
     */
    public function getSize(): int
    {
        return curl_getinfo($this->executedCurl, CURLINFO_SIZE_DOWNLOAD);
    }
}