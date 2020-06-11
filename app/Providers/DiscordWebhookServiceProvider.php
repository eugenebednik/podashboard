<?php

namespace App\Providers;

use App\Services\DiscordWebhookService;
use Illuminate\Support\ServiceProvider;

class DiscordWebhookServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $config = config('discord');

        $this->app->singleton(DiscordWebhookService::class, function ($app) use ($config) {
            return new DiscordWebhookService($config['api_base_url'], $config['app_id'], $config['token']);
        });
    }
}
