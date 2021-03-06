<?php

namespace Monitor\Modules;

use Monitor\Helpers\ResponseFromService;

class ResponseSize
{
    const NO_ERROR_REASON = 'No error';
    const DEFAULT_SIZE_ERROR = 0.3;

    private static $sizeError;

    private $response;

    /**
     * ResponseSize constructor.
     *
     * @param ResponseFromService $response
     */
    public function __construct(ResponseFromService $response)
    {
        self::$sizeError = getenv('RESPONSE_SIZE_ERROR') ?: self::DEFAULT_SIZE_ERROR;
        echo self::$sizeError;
        $this->response = $response;
    }

    /**
     * Return the change size reason.
     *
     * @param array $storageSizes
     *
     * @return string
     */
    public function getSizeDifferenceAsReason(array $storageSizes): string
    {
        if (empty($storageSizes)) {
            return self::NO_ERROR_REASON;
        }

        $average = $this->calculateAverage($storageSizes);

        $diff = $this->calculatePercentageDiff($average, $this->response->getSize());

        $comparative = $this->response->getSize() > $average ? 'bigger' : 'smaller';

        $reason = $diff / 100 > self::$sizeError ? sprintf(
            'Response size %u%% %s',
            $diff,
            $comparative
        ) : self::NO_ERROR_REASON;

        return $reason;
    }

    /**
     * Return the difference in percentage.
     *
     * @param int $a
     * @param int $b
     *
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
     *
     * @return int
     */
    private function calculateAverage(array $sizes): int
    {
        return array_sum($sizes) / count($sizes);
    }
}
