<?php

namespace Monitor\Modules;

class ResponseSize
{
    const SIZE_ERROR = 0.1;

    /**
     * Return the change size reason.
     *
     * @param int $responseSize
     * @param array $storageSizes
     * @return string
     * @throws \Exception
     */
    public function getSizeDifferenceAsReason(int $responseSize, array $storageSizes): string
    {
        if (empty($storageSizes)) {
            throw new \Exception('There are no enough sizes to analyse.');
        }

        $average = $this->calculateAverage($storageSizes);

        $diff = $this->calculatePercentageDiff($average, $responseSize);

        $comparative = $responseSize > $average ? 'bigger' : 'smaller';

        $reason = $diff / 100 > 0.1 ? sprintf('Response size %u%% %s', $diff, $comparative) : 'No error';

        return $reason;
    }

    private function calculatePercentageDiff(int $a, int $b): int
    {
        return abs(($a - $b) / ($a)) * 100;
    }

    /**
     * Returns the average size.
     *
     * @param array $sizes
     * @return int
     */
    private function calculateAverage(array $sizes): int
    {
        return array_sum($sizes) / count($sizes);
    }
}