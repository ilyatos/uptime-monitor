<?php

namespace Monitor\Helpers;

use PhpParser\Node\Arg;

final class BotNotification
{
    const EMOJI_WARNING = "\u{26A0}";
    const EMOJI_ERROR = "\u{1F6D1}";

    private $ch;
    private $message = '';

    /**
     * BotNotification constructor with curl initialisation.
     */
    public function __construct()
    {
        $this->ch = curl_init(getenv('BOT_URL'));
    }

    /**
     * Send a message to Bot.
     *
     * @return bool
     */
    public function sendMessage(): bool
    {
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query(['message' => $this->message]));

        return curl_exec($this->ch);
    }

    /**
     * Add a warning message.
     *
     * @param string $warning
     *
     * @return void
     */
    public function addWarningMessage(string $warning): void
    {
        $this->message .= self::EMOJI_WARNING . $warning . PHP_EOL;
    }

    /**
     * Add an error message.
     *
     * @param string $error
     *
     * @return void
     */
    public function addErrorMessage(string $error): void
    {
        $this->message .= self::EMOJI_ERROR . $error . PHP_EOL;
    }
}
