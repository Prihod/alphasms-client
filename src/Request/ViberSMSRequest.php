<?php

namespace AlphaSMS\Request;

class ViberSMSRequest extends ViberRequest
{
    protected string $reqType = 'viber+sms';
    protected string $smsMessage;
    protected string $smsSender;

    public function __construct()
    {
        parent::__construct();
        $this->setType(self::TYPE_TEXT_IMAGE_LINK);
    }

    public function setType(string $type): self
    {
        return parent::setType(self::TYPE_TEXT_IMAGE_LINK);
    }


    public function setSMSSender(string $sender): self
    {
        $this->smsSender = $sender;
        return $this;
    }

    public function setSMSMessage(string $message): self
    {
        $this->smsMessage = $message;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'sms_message' => $this->smsMessage,
                'sms_signature' => $this->smsSender,
            ]
        );

    }

}

