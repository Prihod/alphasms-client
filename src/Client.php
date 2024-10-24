<?php

namespace AlphaSMS;

use AlphaSMS\Exception\RequestException;
use AlphaSMS\Request\RequestInterface;
use AlphaSMS\Response\Response;
use AlphaSMS\Response\ResponseInterface;
use GuzzleHttp\Client as HTTPClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Client to use the alphasms.ua API
 *
 * @link https://sms-pub.s3.eu-central-1.amazonaws.com/manual/alphasms-api-json.1.6.pdf
 */
class Client
{
    protected const API_URL = 'https://alphasms.ua';
    protected string $apiKey;
    protected ClientInterface $client;

    public function __construct(string $apiKey)
    {
        $this->setApiKey($apiKey);
        $this->client = new HTTPClient(
            [
                'base_uri' => self::API_URL,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     *
     * @return Client
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @param ResponseInterface[]|RequestInterface $request
     * @param array                                $options
     *
     * @return ResponseInterface
     * @throws RequestException
     */
    public function execute($request, array $options = []): ResponseInterface
    {
        try {
            $options = array_merge(
                ['json' => $this->prepareRequestData($request)],
                $options
            );
            $response = $this->client->post('/api/json.php', $options);
            return Response::fromJson($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            throw new RequestException(
                $request,
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @param RequestInterface[]|RequestInterface $request
     *
     * @return array
     */
    protected function prepareRequestData($request): array
    {
        if ($request instanceof RequestInterface) {
            $request = [$request];
        }

        $data = [];
        foreach ($request as $item) {
            $data[] = $item->toArray();
        }

        return [
            'auth' => $this->getApiKey(),
            'data' => $data,
        ];
    }
}