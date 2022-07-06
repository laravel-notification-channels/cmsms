<?php

declare(strict_types=1);

namespace NotificationChannels\Cmsms;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Foundation\Application;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\Cmsms\Exceptions\InvalidConfiguration;

class CmsmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CmsmsChannel::class)
            ->needs(CmsmsClient::class)
            ->give(function () {
                if (is_null($productToken = config('services.cmsms.product_token'))) {
                    throw InvalidConfiguration::configurationNotSet();
                }

                return new CmsmsClient(new GuzzleClient(), $productToken);
            });

        $this->app->afterResolving(ChannelManager::class, static function (ChannelManager $manager) {
            $manager->extend('cmsms', static fn (Application $app) => $app->make(CmsmsChannel::class));
        });
    }
}
