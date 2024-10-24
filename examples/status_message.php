<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use AlphaSMS\Client;
use AlphaSMS\Exception\RequestException;
use AlphaSMS\Request\StatusMessageRequest;

$apiKey = '7d514e5b-81e9-40fb-8a9e-7105ffddd3ed';
$client = new Client($apiKey);

// Приклад запитів статусу повідомлення
$request = (new StatusMessageRequest())
    ->setMessageId(12332);

try {
    $response = $client->execute($request);
    print_r($response->toArray());
} catch (RequestException $e) {
    echo $e->getMessage();
    print_r($e->request->toArray());
}
