<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use AlphaSMS\Client;
use AlphaSMS\Exception\RequestException;
use AlphaSMS\Request\BalanceRequest;

$apiKey = '7d514e5b-81e9-40fb-8a9e-7105ffddd3ed';
$client = new Client($apiKey);

try {
    // Приклад запиту перевірки баланса
    $request = new BalanceRequest();
    $response = $client->execute($request);

    if ($response->isSuccess()) {
        echo "Response to array:\n";
        print_r($response->toArray());
        echo "Response get data:\n";
        print_r($response->getData());
        echo "Response get all entries:\n";
        print_r($response->getEntries());
        echo "Response get first entry:\n";
        $entry = $response->getFirstEntry();
        print_r($entry);

        if ($entry->isSuccess()) {
            echo "Entry to array:\n";
            print_r($entry->toArray());
            echo "Entry get data:\n";
            print_r($entry->getData());
        } else {
            echo "Entry Error: {$entry->getError()}";
        }
    } else {
        echo "Response Error: {$response->getError()}";
    }
} catch (RequestException $e) {
    echo "Exception: {$e->getMessage()}\n";
    print_r($e->request->toArray());
}