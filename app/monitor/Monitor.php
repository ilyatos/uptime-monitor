<?php

namespace Monitor;

use App\Models\Reason;
use App\Models\Response;
use App\Models\Service;
use Monitor\Exceptions\CurlExecutionException;
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
     *
     * @throws CurlExecutionException
     */
    public function run(): void
    {
        $services = Service::all();

        foreach ($services as $service) {
            $this->runForOne($service);
        }

        $this->notificationBot->sendMessage();
    }

    /**
     * The Monitor's runner for one service.
     *
     * @param array $service
     *
     * @throws CurlExecutionException
     */
    public function runForOne(Service $service): void
    {
        if ($service->alias === null) {
            $service->alias = $service->url;
        }

        $serviceResponseData = $this->checkService($service->id, $service->url, $service->alias);
        Response::create($serviceResponseData);
    }

    /**
     * Check if service is available or not.
     *
     * @param int    $serviceId
     * @param string $serviceUrl
     * @param string $serviceAlias
     *
     * @throws CurlExecutionException
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

            $noErrorId = Reason::firstOrCreate(['reason' => self::NO_ERROR_REASON])->id;

            $responseSizeAndTime = Response::query()->where([
                ['service_id', '=', $serviceId],
                ['reason_id', '=', $noErrorId]
            ])->get(['response_size', 'response_time']);

            $timeReason = $responseTimeModule
                ->getTimeDifferenceAsReason($responseSizeAndTime->pluck('response_time')->toArray());
            $sizeReason = $responseSizeModule
                ->getSizeDifferenceAsReason($responseSizeAndTime->pluck('response_size')->toArray());

            $reason = $this->getFinalReason($timeReason, $sizeReason);

            if ($reason != self::NO_ERROR_REASON) {
                $this->notificationBot->addWarningMessage($reason . ' for ' . $serviceAlias);
            }
        } else {
            $result['availability'] = 0;

            $reason = $httpStatusCodeModule->getCodeName();

            $this->notificationBot->addErrorMessage($reason . ' for ' . $serviceAlias);
        }

        $result['reason_id'] = Reason::firstOrCreate(['reason' => $reason])->id;

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
