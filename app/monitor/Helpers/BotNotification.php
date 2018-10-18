<?php

namespace Monitor\Helpers;

final class BotNotification
{
    private $ch;

    /**
     * BotNotification constructor with curl initialisation.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->ch = curl_init($url);
    }

    /**
     * Send a message to initialised url.
     *
     * @param string $massage
     * @return bool
     */
    public function send(string $massage)
    {
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query(['message' => $massage]));
        return curl_exec($this->ch);
    }
}