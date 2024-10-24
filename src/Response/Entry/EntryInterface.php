<?php

namespace AlphaSMS\Response\Entry;

interface EntryInterface
{
    public function isSuccess(): bool;

    public function getData(): array;

    public function getError(): string;

    public function toArray(): array;
}