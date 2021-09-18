<?php

namespace App\ShortUrl\Facades;

use App\ShortUrl\MakeHasher;
use Illuminate\Support\Facades\Facade;

class Hasher extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MakeHasher::class;
    }
}