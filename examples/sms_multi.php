<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use AlphaSMS\Client;
use AlphaSMS\Exception\RequestException;
use AlphaSMS\Request\SMSRequest;

$apiKey = '7d514e5b-81e9-40fb-8a9e-7105ffddd3ed';
$client = new Client($apiKey);

$requests = [];
$requests[] = (new SMSRequest())
    ->setPhone('380971234567')
    ->setSender('SMSTest')
    ->setMessage('Message text to be sent via SMS 1');

$requests[] = (new SMSRequest())
    ->setPhone('380971234568')
    ->setSender('SMSTest')
    ->setMessage('Message text to be sent via SMS 2');

$requests[] = (new SMSRequest())
    ->setPhone('380971234569')
    ->setSender('SMSTest')
    ->setMessage('Message text to be sent via SMS 3');


try {
    $response = $client->execute($requests);
    print_r($response->toArray());
} catch (RequestException $e) {
    echo $e->getMessage();
    print_r($e->request->toArray());
}
