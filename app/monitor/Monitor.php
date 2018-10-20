<?php

namespace Monitor;

use App\Entities\Reason;
use Monitor\Helpers\RequestToService;
use App\Entities\Service;
use App\Entities\Response;
use Monitor\Helpers\BotNotification;
use Monitor\Modules\HttpStatusCode;


class Monitor
{
    private $notificationBot;
    private $notificationMessage;

    private $httpStatusCode;

    /**
     * Boot modules.
     */
    public function __construct()
    {
        $this->notificationBot = new BotNotification();
        $this->httpStatusCode = new HttpStatusCode();
    }

    /**
     * The Monitor's runner.
     */
    public function run()
    {
        $services = Service::all(['id', 'alias', 'url']);

        foreach ($services as $service) {
            $response = $this->checkService($service['id'], $service['url'], $service['alias']);
            Response::store($response);
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

        if ($this->httpStatusCode->match($responseCode, 200)) {
            $result['availability'] = 1;
        } else {
            $result['availability'] = 0;
        }

        try {
            $codeName = $this->httpStatusCode->getCodeName($responseCode);
        } catch (\Exception $e) {
            $codeName = 'Undefined situation';
        }

        $result['reason_id'] = Reason::getReasonId($codeName);

        return $result;
    }

}