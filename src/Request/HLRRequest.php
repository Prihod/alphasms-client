<?php

namespace AlphaSMS\Request;

class HLRRequest extends Request
{
    protected string $reqType = 'hlr';
    protected string $phone;


    public function setPhone(string $phone): self
    {
        $this->phone = self::preparePhone($phone);
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(), [
                'phone' => self::preparePhone($this->phone),
            ]
        );
    }

}

