<?php

namespace Monitor\Modules;

use Monitor\Helpers\ResponseFromService;

class ResponseSize
{
    const SIZE_ERROR = 0.1;

    private $response;

    /**
     * ResponseSize constructor.
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
    public function getSizeDifferenceAsReason(array $storageSizes): string
    {
        if (empty($storageSizes)) {
            return 'No error';
        }

        $average = $this->calculateAverage($storageSizes);

        $diff = $this->calculatePercentageDiff($average, $this->response->getSize());

        $comparative = $this->response->getSize() > $average ? 'bigger' : 'smaller';

        $reason = $diff / 100 > 0.1 ? sprintf('Response size %u%% %s', $diff, $comparative) : 'No error';

        return $reason;
    }

    /**
     * Return the difference in percentage.
     *
     * @param int $a
     * @param int $b
     * @return int
     */
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