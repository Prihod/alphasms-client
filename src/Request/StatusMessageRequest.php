<?php

namespace AlphaSMS\Request;

class StatusMessageRequest extends Request
{
    protected string $reqType = 'status';
    protected int $id;

    public function setMessageId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


    public function toArray(): array
    {
        return array_merge(
            parent::toArray(), [
            'id' => $this->id,
            ]
        );
    }
}

