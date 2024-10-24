<?php

namespace AlphaSMS\Response\Entry;

class Entry implements EntryInterface
{
    const E_INVALID_ENTRY = 'Invalid entry data';
    protected bool $success;
    protected array $data;
    protected string $error;

    public function __construct(bool $success, array $data, string $error = '')
    {
        $this->success = $success;
        $this->data = $data;
        $this->error = $error;
    }

    public static function fromArray(array $arr): self
    {
        if (!$arr) {
            return new Entry(false, [], self::E_INVALID_ENTRY);
        }

        if (isset($arr['success']) && $arr['success'] === true) {
            return new Entry(true, $arr['data'] ?? []);
        }

        return new Entry(false, [], $arr['error'] ?? '');
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getData(): array
    {
        return $this->data;
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