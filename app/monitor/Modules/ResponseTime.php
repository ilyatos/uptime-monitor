<?php

namespace Monitor\Modules;

class ResponseTime
{
    const TIME_ERROR = 0.2;

    /**
     * Return the change size reason.
     *
     * @param int $responseSize
     * @param array $storageSizes
     * @return string
     */
    public function getTimeDifferenceAsReason(float $responseTime, array $storageTime): string
    {
        if (empty($storageTime)) {
            throw new \Exception('There are no enough sizes to analyse.');
        }

        $average = $this->calculateAverage($storageTime);

        $diff = $this->calculatePercentageDiff($average, $responseTime);

        $comparative = $responseTime > $average ? 'longer' : 'faster';

        $reason = $diff / 100 > self::TIME_ERROR ? sprintf('Response time %u%% %s', $diff, $comparative) : 'No error';

        return $reason;

    }

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