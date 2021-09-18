<?php

namespace App\Providers;

use App\FormFields\DateTime;
use App\FormFields\MyMultiSelect;
use App\FormFields\SlugFormField;
use App\FormFields\SummernoteFormField;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Voyager::addFormField(SummernoteFormField::class);
        Voyager::addFormField(SlugFormField::class);
        Voyager::addFormField(MyMultiSelect::class);
        Voyager::addFormField(DateTime::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
