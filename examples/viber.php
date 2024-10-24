<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use AlphaSMS\Client;
use AlphaSMS\Exception\RequestException;
use AlphaSMS\Request\ViberRequest;

$apiKey = '7d514e5b-81e9-40fb-8a9e-7105ffddd3ed';
$client = new Client($apiKey);

// Приклад запитів повідомлення viber з текстом
$request = (new ViberRequest())
    ->setType(ViberRequest::TYPE_TEXT)
    ->setPhone('380971234567')
    ->setSender("ViberTest")
    ->setMessage("Message text to send via Viber");

// Приклад запитів повідомлення viber з зображенням
$request = (new ViberRequest())
    ->setType(ViberRequest::TYPE_IMAGE)
    ->setPhone('380971234567')
    ->setSender("ViberTest")
    ->setImage('https://...... /storage/images/json.png');

// Приклад запитів повідомлення viber текстом та посиланням
$request = (new ViberRequest())
    ->setType(ViberRequest::TYPE_TEXT_LINK)
    ->setPhone('380971234567')
    ->setSender("ViberTest")
    ->setMessage("Message text to send via Viber")
    ->setLink('https://alphasms.ua')
    ->setButtonText('Test');

// Приклад запитів повідомлення viber з текстом, зображенням та посиланням
$request = (new ViberRequest())
    ->setType(ViberRequest::TYPE_TEXT_IMAGE_LINK)
    ->setPhone('380971234567')
    ->setSender("ViberTest")
    ->setMessage("Message text to send via Viber")
    ->setLink('https://alphasms.ua')
    ->setButtonText('Test')
    ->setImage('"https://...... /storage/images/json.png');


try {
    $response = $client->execute($request);
    print_r($response->toArray());
} catch (RequestException $e) {
    echo $e->getMessage();
    print_r($e->request->toArray());
}
