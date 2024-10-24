<?php

namespace AlphaSMS\Request;

class RCSSMSRequest extends RCSRequest
{
    protected string $reqType = 'rcs+sms';
    protected string $smsMessage;
    protected string $smsSender;

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

