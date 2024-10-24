<?php

namespace AlphaSMS\Request;

class ViberRequest extends Request
{
    public const TYPE_TEXT = 'text';
    public const TYPE_IMAGE = 'image';
    public const TYPE_TEXT_LINK = 'text+link';
    public const TYPE_TEXT_IMAGE_LINK = 'text+image+link';

    protected string $reqType = 'viber';
    protected string $id;
    protected string $type;
    protected string $phone;
    protected string $sender;
    protected string $message;
    protected string $link;
    protected string $image;
    protected string $buttonText = '';

    public function __construct()
    {
        $this->buttonText = '';
        $this->type = self::TYPE_TEXT;
        $this->id = $this->generateId();
    }


    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
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
            parent::toArray(),
            [
                'id' => $this->id,
                'phone' => $this->phone,
                'viber_type' => $this->type,
                'viber_signature' => $this->sender,
            ],
            $this->getDataByType($this->type)
        );

    }

    protected function getDataByType(string $type): array
    {
        $data = [];
        switch ($type) {
        case self::TYPE_TEXT:
            $data['viber_message'] = $this->message;
            break;
        case self::TYPE_IMAGE:
            $data['viber_image'] = $this->image;
            break;
        case self::TYPE_TEXT_LINK:
        case self::TYPE_TEXT_IMAGE_LINK:
            $data['viber_link'] = $this->link;
            $data['viber_message'] = $this->message;
            $data['viber_button'] = $this->buttonText;

            if ($this->type === self::TYPE_TEXT_IMAGE_LINK) {
                $data['viber_image'] = $this->image;
            }
            break;
        }
        return $data;
    }
}