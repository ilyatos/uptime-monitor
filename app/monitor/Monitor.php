<?php

namespace Monitor;

use App\Entities\Reason;
use App\Entities\Response;
use App\Entities\Service;
use Monitor\Helpers\BotNotification;
use Monitor\Helpers\RequestToService;
use Monitor\Modules\HttpStatusCode;
use Monitor\Modules\ResponseSize;
use Monitor\Modules\ResponseTime;

class Monitor
{
    const NO_ERROR_REASON = 'No error';

    private $requestToService;
    private $notificationBot;
    private $notificationMessage;

    /**
     * Boot modules.
     */
    public function __construct()
    {
        $this->notificationBot = new BotNotification();
        $this->requestToService = new RequestToService();
    }

    /**
     * The Monitor's runner.
     */
    public function run(): void
    {
        $services = Service::all(['id', 'alias', 'url']);

        foreach ($services as $service) {
            //temporary solution
            try {
                $this->runForOne($service);
            } catch (\Exception $e) {
                echo "Exception occurs for service: " . $service['url'] . ' ' . $e->getMessage();
                continue;
            }
        }
    }

    /**
     * The Monitor's runner for one service.
     *
     * @param array $service
     */
    public function runForOne(array $service): void
    {
        $serviceResponseData = $this->checkService($service['id'], $service['url'], $service['alias']);
        Response::store($serviceResponseData);
    }

    /**
     * Check if service is available or not.
     *
     * @param int $serviceId
     * @param string $serviceUrl
     * @param string $serviceAlias
     *
     * @return array
     */
    private function checkService(int $serviceId, string $serviceUrl, string $serviceAlias): array
    {
        $response = $this->requestToService->send($serviceUrl);

        $httpStatusCodeModule = new HttpStatusCode($response);
        $responseSizeModule = new ResponseSize($response);
        $responseTimeModule = new ResponseTime($response);

        $result = [
            'service_id' => $serviceId,
            'response_time' => $response->getTime(),
            'response_size' => $response->getSize(),
        ];

        if ($httpStatusCodeModule->match('^2\d{2}$')) {
            $result['availability'] = 1;

            $noErrorId = Reason::findOrCreateReasonId(self::NO_ERROR_REASON);

            $previousAvailableSizes = Response::find(['response_size'])->where([
                ['service_id' => $serviceId, 'AND'],
                ['reason_id' => $noErrorId]
            ])->getAll(\PDO::FETCH_COLUMN);

            $previousAvailableTime = Response::find(['response_time'])->where([
                ['service_id' => $serviceId, 'AND'],
                ['reason_id' => $noErrorId]
            ])->getAll(\PDO::FETCH_COLUMN);

            $timeReason = $responseTimeModule->getTimeDifferenceAsReason($previousAvailableTime);
            $sizeReason = $responseSizeModule->getSizeDifferenceAsReason($previousAvailableSizes);

            $reason = $this->getFinalReason($timeReason, $sizeReason);
        } else {
            $result['availability'] = 0;

            $reason = $httpStatusCodeModule->getCodeName();
        }

        $result['reason_id'] = Reason::findOrCreateReasonId($reason);

        return $result;
    }

    /**
     * @param mixed ...$reasons
     *
     * @return string
     */
    private function getFinalReason(...$reasons): string
    {
        $reasons = array_filter($reasons, function ($reason) {
            return $reason != self::NO_ERROR_REASON;
        });

        return empty($reasons) ? self::NO_ERROR_REASON : implode(',', $reasons);
    }
}
