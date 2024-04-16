<?php

namespace Ruark\LaravelInn;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class InnServiceProvider extends ServiceProvider
{
    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/inn.php' => config_path('inn.php'),
        ]);

        $validator = $this->app->make('validator');
        $validator->extend('inn', function ($attribute, $value, $parameters) {
            return (new InnValidator)->validate($value, $parameters);
        }, InnValidator::getMessageBag());
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/inn.php', 'inn'
        );
    }
}
