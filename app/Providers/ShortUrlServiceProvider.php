<?php

namespace App\Providers;

use App\ShortUrl\MakeHasher;
use Illuminate\Support\ServiceProvider;

class ShortUrlServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('hasher',function(){
            return new MakeHasher();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
