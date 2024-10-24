# alphasms-client

This is client library for the [alphasms.ua](https://alphasms.ua/) API.

## 1. Prerequisites

* PHP 7.4 or later

## 2. Installation

The alphasms-client can be installed using Composer by running the following command:

```sh
composer require prihod/alphasms-client
```

## 3. Initialization

Create Client object using the following code:

```php
<?php

use AlphaSMS\Client;

require_once __DIR__ . '/vendor/autoload.php';

$apiKey = '7d514e5b-81e9-40fb-8a9e-7105ffddd3ed';
$client = new Client($apiKey);
```
## 4. API Requests

### 4.1. Request get balance

```php
use AlphaSMS\Request\BalanceRequest;
use AlphaSMS\Exception\RequestException;

try {
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
```
### 4.2. Request to send SMS

```php
use AlphaSMS\Request\SMSRequest;

$request = (new SMSRequest())
    ->setPhone('380971234567')
    ->setSender('SMSTest')
    ->setMessage('Message text to be sent via SMS');

$response = $client->execute($request);
...
```

### 4.3. Multiple SMS sending request

```php
use AlphaSMS\Request\SMSRequest;

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

$response = $client->execute($requests);
...
```
### 4.4. Request to send a message to Viber

#### 4.4.1. Text message

```php
use AlphaSMS\Request\ViberRequest;

$request = (new ViberRequest())
    ->setType(ViberRequest::TYPE_TEXT)
    ->setPhone('380971234567')
    ->setSender("ViberTest")
    ->setMessage("Message text to send via Viber");

$response = $client->execute($request);
...
```

#### 4.4.2. Image

```php
use AlphaSMS\Request\ViberRequest;

$request = (new ViberRequest())
    ->setType(ViberRequest::TYPE_IMAGE)
    ->setPhone('380971234567')
    ->setSender("ViberTest")
    ->setImage('https://...... /storage/images/json.png');

$response = $client->execute($request);
...
```
#### 4.4.3. Text with link

```php
use AlphaSMS\Request\ViberRequest;

$request = (new ViberRequest())
    ->setType(ViberRequest::TYPE_TEXT_LINK)
    ->setPhone('380971234567')
    ->setSender("ViberTest")
    ->setMessage("Message text to send via Viber")
    ->setLink('https://alphasms.ua')
    ->setButtonText('Test');

$response = $client->execute($request);
...
```

#### 4.4.4. Text with link and image

```php
use AlphaSMS\Request\ViberRequest;

$request = (new ViberRequest())
    ->setType(ViberRequest::TYPE_TEXT_IMAGE_LINK)
    ->setPhone('380971234567')
    ->setSender("ViberTest")
    ->setMessage("Message text to send via Viber")
    ->setLink('https://alphasms.ua')
    ->setButtonText('Test')
    ->setImage('"https://...... /storage/images/json.png');

$response = $client->execute($request);
...
```
### 4.5. Request to send RCS

```php
use AlphaSMS\Request\RCSRequest;

$request = (new RCSRequest())
    ->setPhone('380971234567')
    ->setSender('RCSTest')
    ->setMessage('Message text to be sent via RCS')
    ->setImage('https://alphasms.ua/storage/images/json.png')
    ->setLink('https://alphasms.ua')
    ->setButtonText('Test');
    
$response = $client->execute($request);
...
```

### 4.6. Request voice OTP

```php
use AlphaSMS\Request\VoiceOTPRequest;

$request = (new VoiceOTPRequest())
    ->setPhone('380971234567');

$response = $client->execute($request);
...
```
### 4.7. Request HLR

```php
use AlphaSMS\Request\HLRRequest;

$request = (new HLRRequest())
    ->setPhone('380971234567');

$response = $client->execute($request);
...
```

### 4.8. Request status message

```php
use AlphaSMS\Request\StatusMessageRequest;

$request = (new StatusMessageRequest())
    ->setMessageId(12332);

$response = $client->execute($request);
...
```
## 5. Links

* API [docs](https://sms-pub.s3.eu-central-1.amazonaws.com/manual/alphasms-api-json.1.6.pdf)
