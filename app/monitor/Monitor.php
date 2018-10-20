<?php

namespace Monitor;

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
        $services = Service::all(['alias', 'url']);

        foreach ($services as $service) {
            $this->checkService($service['id'], $service['url'], $service['alias']);
        }
    }

    /**
     * Check if service is available or not.
     *
     * @param string $serviceUrl
     * @param string $serviceAlias
     */
    private function checkService(int $serviceId, string $serviceUrl, string $serviceAlias)
    {
        $checker = new RequestToService($serviceUrl);

        $responseCode = $checker->getResponseHttpCode();

        if ($this->httpStatusCode->match($responseCode, 200)) {

        } else {

        }
    }

}