<?php

namespace Monitor;

use App\Entities\Reason;
use Monitor\Helpers\RequestToService;
use App\Entities\Service;
use App\Entities\Response;
use Monitor\Helpers\BotNotification;
use Monitor\Modules\HttpStatusCode;
use Monitor\Modules\ResponseSize;


class Monitor
{
    private $notificationBot;
    private $notificationMessage;

    private $httpStatusCodeModule;
    private $responseSizeModule;

    /**
     * Boot modules.
     */
    public function __construct()
    {
        $this->notificationBot = new BotNotification();
        $this->httpStatusCodeModule = new HttpStatusCode();
        $this->responseSizeModule = new ResponseSize();
    }

    /**
     * The Monitor's runner.
     */
    public function run()
    {
        $services = Service::all(['id', 'alias', 'url']);

        foreach ($services as $service) {
            $serviceResponseData = $this->checkService($service['id'], $service['url'], $service['alias']);
            Response::store($serviceResponseData);
        }
    }

    /**
     * Check if service is available or not.
     *
     * @param string $serviceUrl
     * @param string $serviceAlias
     */
    private function checkService(int $serviceId, string $serviceUrl, string $serviceAlias): array
    {
        $checker = new RequestToService($serviceUrl);

        $responseCode = $checker->getResponseHttpCode();
        $responseTime = $checker->getResponseTime();
        $responseSize = $checker->getResponseSize();

        $result = [
            'service_id' => $serviceId,
            'response_time' => $responseTime,
            'response_size' => $responseSize,
        ];

        if ($this->httpStatusCodeModule->match($responseCode, '2\d{2}')) {
            $result['availability'] = 1;

            $noErrorId = Reason::getReasonId('No error');

            $previousAvailableSizes = Response::find(['response_size'])->where([
                ['service_id' => $serviceId, 'AND'],
                ['reason_id' => $noErrorId]
            ])->getAll(\PDO::FETCH_COLUMN);

            try {
                $reason = $this->responseSizeModule->getSizeDifferenceAsReason($responseSize, $previousAvailableSizes);
            } catch (\Exception $e) {
                $reason = 'No error';
            }
        } else {
            $result['availability'] = 0;

            try {
                $reason = $this->httpStatusCodeModule->getCodeName($responseCode);
            } catch (\Exception $e) {
                $reason = 'Undefined situation';
            }
        }

        $result['reason_id'] = Reason::getReasonId($reason);

        return $result;
    }

}