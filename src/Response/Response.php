<?php

namespace AlphaSMS\Response;

use AlphaSMS\Response\Entry\Entry;
use AlphaSMS\Response\Entry\EntryInterface;

class Response implements ResponseInterface
{
    const E_INVALID_JSON = 'Invalid json';
    const E_UNKNOWN_ERROR = 'Unknown error';
    const E_BAD_RESPONSE = 'Bad response';

    protected bool $success;
    protected array $data = [];
    protected array $entries = [];
    protected string $error;

    public static function fromJson(string $json): self
    {
        if (!$json) {
            return new Response(false, [], self::E_BAD_RESPONSE);
        }

        $responseData = json_decode($json, true);
        if (!$responseData) {
            return new Response(false, [], self::E_INVALID_JSON);
        }

        if (isset($responseData['success']) && $responseData['success'] === true) {
            return new Response(true, $responseData['data']);
        }

        return new Response(false, [], self::prepareError($responseData));
    }

    protected static function prepareError(array $data): string
    {
        $error = self::E_UNKNOWN_ERROR;
        if ($data && isset($data['error'])) {
            $error = $data['error'];
        }

        return $error;
    }


    public function __construct(bool $success, array $data, string $error = '')
    {
        $this->success = $success;
        $this->data = $data;
        $this->error = $error;

        foreach ($this->data as $entry) {
            $this->entries[] = Entry::fromArray($entry);
        }
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getEntries(): array
    {
        return $this->entries;
    }

    public function getFirstEntry(): ?EntryInterface
    {
        return $this->entries ? $this->entries[0] : null;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->isSuccess(),
            'data' => $this->getData(),
            'error' => $this->getError(),
        ];
    }

    public function __toString()
    {
        return (string)json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

}