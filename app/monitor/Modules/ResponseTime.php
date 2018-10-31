<?php

namespace Monitor\Modules;

use Monitor\Helpers\ResponseFromService;

class ResponseTime
{
    const NO_ERROR_REASON = 'No error';
    const DEFAULT_TIME_ERROR = 0.3;

    private static $timeError;

    private $response;

    /**
     * ResponseTime constructor.
     *
     * @param ResponseFromService $response
     */
    public function __construct(ResponseFromService $response)
    {
        self::$timeError = getenv('RESPONSE_TIME_ERROR') ?: self::DEFAULT_TIME_ERROR;
        $this->response = $response;
    }

    /**
     * Returns the change size reason.
     *
     * @param int   $responseSize
     * @param array $storageSizes
     *
     * @return string
     */
    public function getTimeDifferenceAsReason(array $storageTime): string
    {
        if (empty($storageTime)) {
            return self::NO_ERROR_REASON;
        }

        $average = $this->calculateAverage($storageTime);

        $diff = $this->calculatePercentageDiff($average, $this->response->getTime());

        $comparative = $this->response->getTime() > $average ? 'longer' : 'faster';

        $reason = $diff / 100 > self::$timeError ? sprintf(
            'Response time %u%% %s',
            $diff,
            $comparative
        ) : self::NO_ERROR_REASON;

        return $reason;
    }

    /**
     * Return the difference in percentage.
     *
     * @param float $a
     * @param float $b
     *
     * @return int
     */
    private function calculatePercentageDiff(float $a, float $b): int
    {
        return abs(($a - $b) / ($a)) * 100;
    }

    /**
     * Returns the average time.
     *
     * @param array $time
     *
     * @return float
     */
    private function calculateAverage(array $time): float
    {
        return array_sum($time) / count($time);
    }
}
