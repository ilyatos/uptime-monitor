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

    public function __construct()
    {
        $this->notificationBot = new BotNotification();
        $this->httpStatusCode = new HttpStatusCode();
    }

    /**
     * Run the monitor.
     */
    public function run()
    {
        $services = Service::all(['alias', 'url']);

        foreach ($services as $service) {
            $this->checkService($service['url'], $service['alias']);
        }


    }

    private function checkService(string $serviceUrl, string $serviceAlias)
    {


    }

}