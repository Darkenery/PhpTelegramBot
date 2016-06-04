<?php

/**
 * Created by PhpStorm.
 * User: darke_000
 * Date: 03.06.2016
 * Time: 21:33
 */

namespace Darkenery\Bot;



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





}