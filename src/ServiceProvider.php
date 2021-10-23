<?php

namespace Daynnnnn\Statamic\SwrCache;

use Illuminate\Cache\Repository;
use Statamic\Providers\AddonServiceProvider;
use Statamic\StaticCaching\StaticCacheManager;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    public function boot()
    {
        $stratergy = config('statamic.static_caching.strategy');
        $config = config("statamic.static_caching.strategies.$stratergy");

        if ($config['driver'] ?? null === 'swr') {
            $this->app[StaticCacheManager::class]->extend($stratergy, function () use ($stratergy) {
                return new Cachers\SwrCacher($this->app[Repository::class], $this->getConfig($stratergy));
            });
        }
    }
}
