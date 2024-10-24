<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use AlphaSMS\Client;
use AlphaSMS\Exception\RequestException;
use AlphaSMS\Request\VoiceOTPRequest;

$apiKey = '7d514e5b-81e9-40fb-8a9e-7105ffddd3ed';
$client = new Client($apiKey);

// Приклад запитів верифікації номера через дзвінок (voice otp)
$request = (new VoiceOTPRequest())
    ->setPhone('380971234567');

try {
    $response = $client->execute($request);
    print_r($response->toArray());
} catch (RequestException $e) {
    echo $e->getMessage();
    print_r($e->request->toArray());
}
