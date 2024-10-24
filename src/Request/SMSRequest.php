<?php

namespace AlphaSMS\Request;

class SMSRequest extends Request
{
    protected string $reqType = 'sms';
    protected string $id;
    protected string $phone;
    protected string $sender;
    protected string $message;

    public function __construct()
    {
        $this->id = $this->generateId();
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = self::preparePhone($phone);
        return $this;
    }

    public function setSender(string $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(), [
                'id' => $this->id,
                'phone' => $this->phone,
                'sms_signature' => $this->sender,
                'sms_message' => $this->message,
            ]
        );
    }


}

