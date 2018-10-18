<?php

require_once dirname(__DIR__) . '/../vendor/autoload.php';

use Monitor\Entities\Service;
use Monitor\Helpers\CheckService;

$services = Service::all(['url']);

foreach ($services as $service) {
    $availability = 1;
    $reason = '';
    $responseTime = 0;
    $responseSize = 0;

    $serviceUrl = $service['url'];

    $checker = new CheckService($serviceUrl);

    if ($checker->getResponseHttpCode() === 200) {
        $reason = 'No error';
        $responseTime = $checker->getResponseTime();
        $responseSize = $checker->getResponseSize();
        //echo $checker->getExResult();
    } elseif (substr($checker->getResponseHttpCode(),0, 1) == 4) {
        $availability = 0;
        $reason = sprintf('Client error %s', $checker->getResponseHttpCode());
    } elseif (substr($checker->getResponseHttpCode(),0, 1) == 5) {
        $availability = 0;
        $reason = sprintf('Server error %s', $checker->getResponseHttpCode());
    }

    Service::updateWhere([
        'availability' => $availability,
        'reason' => $reason,
        'response_time' => $responseTime,
        'response_size' => $responseSize
    ], 'url', $serviceUrl);

    unset($checker);
}






