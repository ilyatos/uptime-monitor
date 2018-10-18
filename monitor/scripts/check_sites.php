<?php

require_once dirname(__DIR__) . '/../vendor/autoload.php';

use Monitor\Entities\Service;
use Monitor\Helpers\CheckService;
use Monitor\Helpers\BotNotification;

define('WARNING', "\u{26A0}");
define('ERROR', "\u{1F6D1}");

$services = Service::all();

$cbn = new BotNotification('https://notify.bot.ifmo.su/u/NLD3L8DR');
$message = '';

foreach ($services as $service) {
    $availability = 1;
    $reason = '';
    $responseTime = 0;
    $responseSize = 0;

    $serviceUrl = $service['url'];
    $serviceAlias = $service['alias'];

    $checker = new CheckService($serviceUrl);

    if ($checker->getExResult() !== false) {

        $responseCode = $checker->getResponseHttpCode();
        $responseTime = $checker->getResponseTime();
        $responseSize = $checker->getResponseSize();

        if ($responseCode === 200) {
            $reason = 'No error';
        } else {
            if (substr($responseCode,0, 1) == 4) {
                $availability = 0;
                $reason = sprintf('Client error %s', $responseCode);
                $message .= sprintf(WARNING . 'Client error %s – %s', $responseCode, $serviceAlias) . PHP_EOL;
            } elseif (substr($responseCode,0, 1) == 5) {
                $availability = 0;
                $reason = sprintf('Server error %s', $responseCode);
                $message .= sprintf(ERROR . 'Server error %s – %s', $responseCode, $serviceAlias) . PHP_EOL;
            }
        }
    } else {
        $availability = 0;
        $reason = 'No response';
        $message .= sprintf(ERROR . 'No response – %s', $serviceAlias) . PHP_EOL;
    }

    Service::updateWhere([
        'availability' => $availability,
        'reason' => $reason,
        'response_time' => $responseTime,
        'response_size' => $responseSize
    ], 'url', $serviceUrl);

    unset($checker);
}

if ($message !== '') {
    $cbn->send($message);
}








