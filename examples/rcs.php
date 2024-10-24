<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use AlphaSMS\Client;
use AlphaSMS\Exception\RequestException;
use AlphaSMS\Request\RCSRequest;

$apiKey = '7d514e5b-81e9-40fb-8a9e-7105ffddd3ed';
$client = new Client($apiKey);

// Приклад запитів повідомлення rcs
$request = (new RCSRequest())
    ->setPhone('380971234567')
    ->setSender('RCSTest')
    ->setMessage('Message text to be sent via RCS')
    ->setImage('https://alphasms.ua/storage/images/json.png')
    ->setLink('https://alphasms.ua')
    ->setButtonText('Test');

try {
    $response = $client->execute($request);
    print_r($response->toArray());
} catch (RequestException $e) {
    echo $e->getMessage();
    print_r($e->request->toArray());
}
