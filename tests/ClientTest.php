<?php

namespace AlphaSMS\Tests;

use AlphaSMS\Client;
use AlphaSMS\Request\BalanceRequest;
use AlphaSMS\Request\HLRRequest;
use AlphaSMS\Request\RCSRequest;
use AlphaSMS\Request\RCSSMSRequest;
use AlphaSMS\Request\SMSRequest;
use AlphaSMS\Request\StatusMessageRequest;
use AlphaSMS\Request\ViberRequest;
use AlphaSMS\Request\ViberSMSRequest;
use AlphaSMS\Request\VoiceOTPRequest;
use GuzzleHttp\Client as HTTPClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private array $container = [];
    private MockHandler $mock;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = [];
        $this->mock = new MockHandler();
        $history = Middleware::history($this->container);
        $handlerStack = HandlerStack::create($this->mock);
        $handlerStack->push($history);

        $guzzle = new HTTPClient(['handler' => $handlerStack]);

        $this->client = new Client('api_key');
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->client, $guzzle);
    }

    public function testSuccessfulSMSSending()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'id' => 100500,
                        'msg_id' => 123456789,
                        'data' => 1,
                        'parts' => 1,
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new SMSRequest())
            ->setId('100500')
            ->setPhone('380971234567')
            ->setSender('SMSSender')
            ->setMessage('SMSMessage');


        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('100500', $requestBody['data'][0]['id']);
        $this->assertEquals('sms', $requestBody['data'][0]['type']);
        $this->assertEquals('380971234567', $requestBody['data'][0]['phone']);
        $this->assertEquals('SMSSender', $requestBody['data'][0]['sms_signature']);
        $this->assertEquals('SMSMessage', $requestBody['data'][0]['sms_message']);

        $this->assertEquals($responseData, $response->toArray());
    }

    public function testSuccessfulMultiSMSSending()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'id' => 100500,
                        'msg_id' => 123456789,
                        'data' => 1,
                        'parts' => 1,
                    ]
                ],
                [
                    'success' => true,
                    'data' => [
                        'id' => 200500,
                        'msg_id' => 223456789,
                        'data' => 1,
                        'parts' => 1,
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $requests = [];
        $requests[] = (new SMSRequest())
            ->setId('100500')
            ->setPhone('380971234567')
            ->setSender('SMSSender')
            ->setMessage('SMSMessage');

        $requests[] = (new SMSRequest())
            ->setId('200500')
            ->setPhone('380971234567')
            ->setSender('SMSSender')
            ->setMessage('SMSMessage');


        $response = $this->client->execute($requests);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertCount(2, $requestBody['data']);
        $this->assertEquals($responseData, $response->toArray());
    }


    public function testSuccessfulRCSSending()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'id' => 100500,
                        'msg_id' => 123456789,
                        'data' => 1,
                        'parts' => 1,
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new RCSRequest())
            ->setId('100500')
            ->setPhone('380971234567')
            ->setSender('RCSSender')
            ->setMessage('RCSMessage')
            ->setImage('https://alphasms.ua/images/json.png')
            ->setLink('https://alphasms.ua')
            ->setButtonText('Text');


        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('100500', $requestBody['data'][0]['id']);
        $this->assertEquals('rcs', $requestBody['data'][0]['type']);
        $this->assertEquals('380971234567', $requestBody['data'][0]['phone']);
        $this->assertEquals('RCSSender', $requestBody['data'][0]['rcs_signature']);
        $this->assertEquals('RCSMessage', $requestBody['data'][0]['rcs_message']);
        $this->assertEquals('https://alphasms.ua/images/json.png', $requestBody['data'][0]['rcs_image']);
        $this->assertEquals('https://alphasms.ua', $requestBody['data'][0]['rcs_link']);
        $this->assertEquals('Text', $requestBody['data'][0]['rcs_button']);

        $this->assertEquals($responseData, $response->toArray());
    }

    public function testSuccessfulRCSSMSSending()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'id' => 100500,
                        'msg_id' => 123456789,
                        'data' => 1,
                        'parts' => 1,
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new RCSSMSRequest())
            ->setId('100500')
            ->setPhone('380971234567')
            ->setSender('RCSSender')
            ->setMessage('RCSMessage')
            ->setSMSSender('SMSSender')
            ->setSMSMessage('SMSMessage')
            ->setImage('https://alphasms.ua/images/json.png')
            ->setLink('https://alphasms.ua')
            ->setButtonText('Text');


        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('100500', $requestBody['data'][0]['id']);
        $this->assertEquals('rcs+sms', $requestBody['data'][0]['type']);
        $this->assertEquals('380971234567', $requestBody['data'][0]['phone']);
        $this->assertEquals('RCSSender', $requestBody['data'][0]['rcs_signature']);
        $this->assertEquals('RCSMessage', $requestBody['data'][0]['rcs_message']);
        $this->assertEquals('SMSSender', $requestBody['data'][0]['sms_signature']);
        $this->assertEquals('SMSMessage', $requestBody['data'][0]['sms_message']);
        $this->assertEquals('https://alphasms.ua/images/json.png', $requestBody['data'][0]['rcs_image']);
        $this->assertEquals('https://alphasms.ua', $requestBody['data'][0]['rcs_link']);
        $this->assertEquals('Text', $requestBody['data'][0]['rcs_button']);

        $this->assertEquals($responseData, $response->toArray());
    }

    public function testSuccessfulViberTextSending()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'id' => 100500,
                        'msg_id' => 123456789,
                        'data' => 1,
                        'parts' => 1,
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new ViberRequest())
            ->setId('100500')
            ->setType(ViberRequest::TYPE_TEXT)
            ->setPhone('380971234567')
            ->setSender('ViberSender')
            ->setMessage('ViberMessage');


        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('100500', $requestBody['data'][0]['id']);
        $this->assertEquals('viber', $requestBody['data'][0]['type']);
        $this->assertEquals('text', $requestBody['data'][0]['viber_type']);
        $this->assertEquals('380971234567', $requestBody['data'][0]['phone']);
        $this->assertEquals('ViberSender', $requestBody['data'][0]['viber_signature']);
        $this->assertEquals('ViberMessage', $requestBody['data'][0]['viber_message']);

        $this->assertEquals($responseData, $response->toArray());
    }

    public function testSuccessfulViberImageSending()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'id' => 100500,
                        'msg_id' => 123456789,
                        'data' => 1,
                        'parts' => 1,
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new ViberRequest())
            ->setId('100500')
            ->setType(ViberRequest::TYPE_IMAGE)
            ->setPhone('380971234567')
            ->setSender('ViberSender')
            ->setImage('https://images/json.png');


        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('100500', $requestBody['data'][0]['id']);
        $this->assertEquals('viber', $requestBody['data'][0]['type']);
        $this->assertEquals('image', $requestBody['data'][0]['viber_type']);
        $this->assertEquals('380971234567', $requestBody['data'][0]['phone']);
        $this->assertEquals('ViberSender', $requestBody['data'][0]['viber_signature']);
        $this->assertEquals('https://images/json.png', $requestBody['data'][0]['viber_image']);

        $this->assertEquals($responseData, $response->toArray());
    }

    public function testSuccessfulViberTextLinkSending()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'id' => 100500,
                        'msg_id' => 123456789,
                        'data' => 1,
                        'parts' => 1,
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new ViberRequest())
            ->setId('100500')
            ->setType(ViberRequest::TYPE_TEXT_LINK)
            ->setPhone('380971234567')
            ->setSender('ViberSender')
            ->setMessage('ViberMessage')
            ->setLink('https://alphasms.ua')
            ->setButtonText('Text');


        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('100500', $requestBody['data'][0]['id']);
        $this->assertEquals('viber', $requestBody['data'][0]['type']);
        $this->assertEquals('text+link', $requestBody['data'][0]['viber_type']);
        $this->assertEquals('380971234567', $requestBody['data'][0]['phone']);
        $this->assertEquals('ViberSender', $requestBody['data'][0]['viber_signature']);
        $this->assertEquals('ViberMessage', $requestBody['data'][0]['viber_message']);
        $this->assertEquals('https://alphasms.ua', $requestBody['data'][0]['viber_link']);
        $this->assertEquals('Text', $requestBody['data'][0]['viber_button']);

        $this->assertEquals($responseData, $response->toArray());
    }

    public function testSuccessfulViberTextImageLinkSending()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'id' => 100500,
                        'msg_id' => 123456789,
                        'data' => 1,
                        'parts' => 1,
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new ViberRequest())
            ->setId('100500')
            ->setType(ViberRequest::TYPE_TEXT_IMAGE_LINK)
            ->setPhone('380971234567')
            ->setSender('ViberSender')
            ->setMessage('ViberMessage')
            ->setLink('https://alphasms.ua')
            ->setImage('https://images/json.png')
            ->setButtonText('Text');


        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('100500', $requestBody['data'][0]['id']);
        $this->assertEquals('viber', $requestBody['data'][0]['type']);
        $this->assertEquals('text+image+link', $requestBody['data'][0]['viber_type']);
        $this->assertEquals('380971234567', $requestBody['data'][0]['phone']);
        $this->assertEquals('ViberSender', $requestBody['data'][0]['viber_signature']);
        $this->assertEquals('ViberMessage', $requestBody['data'][0]['viber_message']);
        $this->assertEquals('https://alphasms.ua', $requestBody['data'][0]['viber_link']);
        $this->assertEquals('https://images/json.png', $requestBody['data'][0]['viber_image']);
        $this->assertEquals('Text', $requestBody['data'][0]['viber_button']);

        $this->assertEquals($responseData, $response->toArray());
    }

    public function testSuccessfulViberSMSSending()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'id' => 100500,
                        'msg_id' => 123456789,
                        'data' => 1,
                        'parts' => 1,
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new ViberSMSRequest())
            ->setId('100500')
            ->setPhone('380971234567')
            ->setSender('ViberSender')
            ->setMessage('ViberMessage')
            ->setSMSSender('SMSSender')
            ->setSMSMessage('SMSMessage')
            ->setLink('https://alphasms.ua')
            ->setImage('https://images/json.png')
            ->setButtonText('Text');


        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('100500', $requestBody['data'][0]['id']);
        $this->assertEquals('viber+sms', $requestBody['data'][0]['type']);
        $this->assertEquals('text+image+link', $requestBody['data'][0]['viber_type']);
        $this->assertEquals('380971234567', $requestBody['data'][0]['phone']);
        $this->assertEquals('ViberSender', $requestBody['data'][0]['viber_signature']);
        $this->assertEquals('ViberMessage', $requestBody['data'][0]['viber_message']);
        $this->assertEquals('SMSSender', $requestBody['data'][0]['sms_signature']);
        $this->assertEquals('SMSMessage', $requestBody['data'][0]['sms_message']);
        $this->assertEquals('https://alphasms.ua', $requestBody['data'][0]['viber_link']);
        $this->assertEquals('https://images/json.png', $requestBody['data'][0]['viber_image']);
        $this->assertEquals('Text', $requestBody['data'][0]['viber_button']);

        $this->assertEquals($responseData, $response->toArray());
    }

    public function testSuccessfulStatusMessage()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'id' => 12332,
                        'type' => 'viber',
                        'status' => 'READ',
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(
            new Response(200, [], json_encode($responseData))
        );

        $request = (new StatusMessageRequest())
            ->setMessageId(12332);

        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('status', $requestBody['data'][0]['type']);
        $this->assertEquals('12332', $requestBody['data'][0]['id']);


        $this->assertEquals($responseData, $response->toArray());
    }

    public function testSuccessfulBalance()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'amount' => 100500.0123,
                        'currency' => 'UAH',
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(
            new Response(200, [], json_encode($responseData))
        );

        $request = new BalanceRequest();
        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('balance', $requestBody['data'][0]['type']);

        $this->assertEquals($responseData, $response->toArray());
    }

    public function testSuccessfulHLR()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'phone' => 380971234567,
                        'status' => 'DELIVERED',
                        'imsi' => 255010000000000,
                        'mccmnc' => 25501,
                        'ported' => true,
                        'network' => [
                            'origin' => [
                                'name' => 'Kyivstar',
                                'prefix' => 97,
                                'country' => [
                                    'name' => 'Ukraine',
                                    'prefix' => 380,
                                ],
                            ],
                            'ported' => [
                                'name' => 'Vodafone Ukraine (fka MTS)',
                                'prefix' => 66,
                                'country' => [
                                    'name' => 'Ukraine',
                                    'prefix' => 380,
                                ],
                            ],
                        ],
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(
            new Response(200, [], json_encode($responseData))
        );

        $request = (new HLRRequest())
            ->setPhone('380971234567');

        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('hlr', $requestBody['data'][0]['type']);
        $this->assertEquals('380971234567', $requestBody['data'][0]['phone']);


        $this->assertEquals($responseData, $response->toArray());
    }

    public function testSuccessfulVoiceOTP()
    {
        $responseData = [
            'success' => true,
            'data' => [
                [
                    'success' => true,
                    'data' => [
                        'code' => '0471',
                        'price' => 0.2,
                    ]
                ]
            ],
            'error' => '',
        ];

        $this->mock->append(
            new Response(200, [], json_encode($responseData))
        );

        $request = (new VoiceOTPRequest())
            ->setPhone('380971234567');

        $response = $this->client->execute($request);
        $requestInfo = $this->container[0]['request'];
        $requestBody = json_decode($requestInfo->getBody()->getContents(), true);

        $this->assertEquals('POST', $requestInfo->getMethod());
        $this->assertEquals('/api/json.php', $requestInfo->getUri()->getPath());
        $this->assertEquals('api_key', $requestBody['auth']);
        $this->assertEquals('call/otp', $requestBody['data'][0]['type']);
        $this->assertEquals('380971234567', $requestBody['data'][0]['phone']);

        $this->assertEquals($responseData, $response->toArray());
    }

}