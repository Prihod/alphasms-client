<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use AlphaSMS\Client;
use AlphaSMS\Exception\RequestException;
use AlphaSMS\Request\ViberSMSRequest;

$apiKey = '7d514e5b-81e9-40fb-8a9e-7105ffddd3ed';
$client = new Client($apiKey);

// Приклад запитів повідомлення viber з текстом, зображенням, посиланням та переотправкою по sms у разі неможливості доставки
$request = (new ViberSMSRequest())
    ->setPhone('380971234567')
    ->setSender("ViberTest")
    ->setMessage("Message text to send via Viber")
    ->setSMSSender('SMSTest')
    ->setSMSMessage('Message text to be sent via SMS')
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
