<?php

namespace Monitor\Helpers;

final class BotNotification
{
    const BOT_URL = 'https://notify.bot.ifmo.su/u/NLD3L8DR';
    const EMOJI_WARNING = "\u{26A0}";
    const EMOJI_ERROR = "\u{1F6D1}";

    private $ch;

    /**
     * BotNotification constructor with curl initialisation.
     */
    public function __construct()
    {
        $this->ch = curl_init(self::BOT_URL);
    }

    /**
     * Send a message to initialised url.
     *
     * @param string $massage
     * @return bool
     */
    public function sendMessage(string $messsage): bool
    {
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query(['message' => $messsage]));
        return curl_exec($this->ch);
    }

    /**
     * Send a warning message.
     *
     * @param string $message
     * @return bool
     */
    public function sendWarning(string $message): bool
    {
        return $this->sendMessage(self::EMOJI_WARNING . $message);
    }

    /**
     * Send an error message.
     *
     * @param string $message
     * @return bool
     */
    public function sendError(string $message): bool
    {
        return $this->sendMessage(self::EMOJI_ERROR . $message);
    }
}