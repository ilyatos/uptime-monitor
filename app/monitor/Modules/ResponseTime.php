<?php

namespace Monitor\Modules;

use Monitor\Helpers\ResponseFromService;

class ResponseTime
{
    const TIME_ERROR = 0.2;

    private $response;

    /**
     * ResponseTime constructor.
     *
     * @param ResponseFromService $response
     */
    public function __construct(ResponseFromService $response)
    {
        $this->response = $response;
    }

    /**
     * Return the change size reason.
     *
     * @param int $responseSize
     * @param array $storageSizes
     * @return string
     */
    public function getTimeDifferenceAsReason(array $storageTime): string
    {
        if (empty($storageTime)) {
            return 'No error';
        }

        $average = $this->calculateAverage($storageTime);

        $diff = $this->calculatePercentageDiff($average, $this->response->getTime());

        $comparative = $this->response->getTime() > $average ? 'longer' : 'faster';

        $reason = $diff / 100 > self::TIME_ERROR ? sprintf('Response time %u%% %s', $diff, $comparative) : 'No error';

        return $reason;

    }

    /**
     * Return the difference in percentage.
     *
     * @param float $a
     * @param float $b
     * @return int
     */
    private function calculatePercentageDiff(float $a, float $b): int
    {
        return abs(($a - $b) / ($a)) * 100;
    }

    /**
     * Returns the average time.
     *
     * @param array $times
     * @return
     */
    private function calculateAverage(array $time): float
    {
        return array_sum($time) / count($time);
    }
}