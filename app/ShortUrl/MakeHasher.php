<?php

namespace App\ShortUrl;

class MakeHasher
{
    protected $length = 7;

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function generate(): string
    {
        $characters = str_repeat(
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
            $this->length
        );

        return substr(str_shuffle($characters), 0, $this->length);
    }
}