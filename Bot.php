<?php

/**
 * Created by PhpStorm.
 * User: darke_000
 * Date: 03.06.2016
 * Time: 21:33
 */

namespace Darkenery\Bot;

use Darkenery\Bot\Types\Message;
use Darkenery\Bot\Types\User;
use Darkenery\Bot\Types\Chat;



class Bot
{

    protected $token;
    protected $curl;

    const URL_PREFIX = 'https://api.telegram.org/bot';

    public function __construct($token)
    {
        $this->token = $token;
        $this->curl = curl_init();
    }

    public function getUrl()
    {
        return self::URL_PREFIX.$this->token;
    }

    public function callCurl($method, array $data = null)
    {
        $options = [
            CURLOPT_URL => $this->getUrl().'/'.$method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => null,
            CURLOPT_POSTFIELDS => null
        ];

        if ($data) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $data;
        }

        self::executeCurl($options);
    }

    public function executeCurl(array $options)
    {
        curl_setopt_array($this->curl, $options);
        print_r(curl_exec($this->curl));
    }

    public function sendMessage($chatId,
                                $text,
                                $parseMode = null,
                                $disablePreview = false,
                                $disableNotification = false,
                                $replyToMessageId = null
                                )
    {

        $this->callCurl('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => $disablePreview,
            'reply_to_message_id' => (int)$replyToMessageId,
            'disable_notification' => (bool)$disableNotification
        ]);
    }

    public function sendPhoto($chatId,
                              $photo,
                              $caption = null,
                              $disableNotification = false,
                              $replyToMessageId = null,
                              $replyMarkup = null )
    {
        $this->callCurl('sendPhoto', [
            'chat_id' => $chatId,
            'photo' => new \CURLFile(realpath($photo)),
            'caption' => $caption,
            'disable_notification' => $disableNotification,
            'reply_to_message_if' => $replyToMessageId,
            'reply_markup' => $replyMarkup
        ]);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
    }

    public function sendVoice($chatId,
                              $voice,
                              $duration = null,
                              $disableNotification = false,
                              $replyToMessageId = null,
                              $replyMarkup = null)
    {
        $this->callCurl('sendVoice', [
            'chat_id' => $chatId,
            'voice' => new \CURLFile(realpath($voice)),
            'duration' => $duration,
            'disable_notification' => $disableNotification,
            'reply_to_message_id' => $replyToMessageId,
            'reply_markup' => $replyMarkup
        ]);
    }

    public function parseUpdate()
    {

    }

    public function getUpdate()
    {
        return json_decode(file_get_contents("php://input"));
    }

}