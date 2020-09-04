<?php

namespace Kayandra\Hashidable;

use Illuminate\Support\ServiceProvider;

class HashidableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/config/hashidable.php', 'hashidable'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__).'/config/hashidable.php' => config_path('hashidable.php'),
        ], 'hashidable.config');
    }
}
