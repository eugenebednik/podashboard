<?php

namespace App\Providers;

use App\Services\CloudflareService;
use Illuminate\Support\ServiceProvider;

class CloudflareServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $config = config('services.cloudflare');

        $this->app->singleton(CloudflareService::class, function ($app) use ($config) {
            return new CloudflareService(
                $config['base-uri'],
                $config['zone-id'],
                $config['server-ip'],
                $config['email'],
                $config['api-token'],
            );
        });
    }
}