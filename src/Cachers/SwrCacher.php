<?php

namespace Daynnnnn\Statamic\SwrCache\Cachers;

use Daynnnnn\Statamic\SwrCache\Jobs\RevalidatePage;
use Illuminate\Http\Request;
use Statamic\StaticCaching\Cachers\ApplicationCacher;

class SwrCacher extends ApplicationCacher
{
    /**
     * @var string|null
     */
    private $cached;

    /**
     * Check if a page has been cached.
     *
     * @param Request $request
     * @return bool
     */
    public function hasCachedPage(Request $request)
    {
        if (config('statamic.static_swr.revalidate') === true) {
            return (bool) $this->cached = null;
        }

        return (bool) $this->cached = $this->getFromCache($request);
    }

    /**
     * Get a cached page from class variable, before checking cache.
     *
     * @param Request $request
     * @return string|null
     */
    public function getCachedPage(Request $request)
    {
        if (config('statamic.static_swr.revalidate') === true) {
            return null;
        }

        return $this->cached ?? $this->getFromCache($request);
    }

    /**
     * Get a cached page from cache repository.
     *
     * @param Request $request
     * @return string
     */
    private function getFromCache(Request $request)
    {
        $url = $this->getUrl($request);

        $key = $this->makeHash($url);

        $page = $this->cache->get($this->normalizeKey('responses:'.$key));

        $ttl = $this->cache
                    ->getStore()
                    ->getRedis()
                    ->connection('cache')
                    ->ttl($this->cache->getStore()->getPrefix().$this->normalizeKey('responses:'.$key));

        if (!is_null($page) && ($this->config('expiry') - $this->config('stale')) * 60 > $ttl) {
            RevalidatePage::dispatch($request->fullUrl());
        }

        return $page;
    }
}
