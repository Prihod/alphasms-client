<?php

namespace AlphaSMS\Request;

abstract class Request implements RequestInterface
{
    protected string $reqType;

    protected function generateId(): string
    {
        return microtime(true) * 10000;
    }

    public static function preparePhone(string $phone): int
    {
        return (int)preg_replace('/\D/', '', $phone);
    }

    public function toArray(): array
    {
        return [
            'type' => $this->reqType,
        ];
    }

    public function __toString(): string
    {
        return (string)json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}
