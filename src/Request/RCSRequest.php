<?php

namespace AlphaSMS\Request;

class RCSRequest extends Request
{
    protected string $reqType = 'rcs';
    protected string $id;
    protected string $phone;
    protected string $sender;
    protected string $message;
    protected string $link;
    protected string $image;
    protected string $buttonText;

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

    public function setLink(string $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function setButtonText(string $text): self
    {
        $this->buttonText = $text;
        return $this;
    }


    public function toArray(): array
    {
        return array_merge(
            parent::toArray(), [
                'id' => $this->id,
                'phone' => $this->phone,
                'rcs_signature' => $this->sender,
                'rcs_message' => $this->message,
                'rcs_image' => $this->image,
                'rcs_link' => $this->link,
                'rcs_button' => $this->buttonText,
            ]
        );
    }


}

