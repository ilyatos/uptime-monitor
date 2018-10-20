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
            throw new \Exception('There are no storageSizes.');
        }

        $average = $this->calculateAverageBasedOnMax($storageSizes);

        $diff = $this->calculatePercentageDiff($average, $responseSize);

        $reason = $diff / 100 > 0.1 ? sprintf('Response size %u%% bigger', $diff) : 'No error';

        return $reason;
    }

    private function calculatePercentageDiff(int $a, int $b): int
    {
        return abs(($a - $b) / ($a)) * 100;
    }

    /**
     * Returns the average size based on max size with error = 10%.
     *
     * @param array $sizes
     * @return int
     */
    private function calculateAverageBasedOnMax(array $sizes): int
    {
        $maxSize = max($sizes);

        $sizes = array_filter($sizes, function ($size) use ($maxSize) {
            return $size === $maxSize or ($maxSize - $maxSize * self::SIZE_ERROR) < $size;
        });

        return array_sum($sizes) / count($sizes);
    }

    /**
     * Returns the average size based on multiply filtering with error = 10%.
     *
     * @param array $sizes
     * @return int
     */
    private function calculateAverageBasedOnFiltering(array $sizes): int
    {
        $error = 0.4;

        while ($error > self::SIZE_ERROR) {
            $psAverage = array_sum($sizes) / count($sizes);

            $sizes = array_filter($sizes, function ($size) use ($error, $psAverage) {
                return $size > ($psAverage - $psAverage * $error);
            });

            $error -= 0.1;
        }

        return array_sum($sizes) / count($sizes);
    }
}