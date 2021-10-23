
## Statamic SWR Static Cache

Allows you to add stale while revalidate functionality to the application static cache driver.

## Requirements

This add-on currently requires you to use Redis as a cache driver.

Additionally, you'll need to process queue items with a separate worker - if you're using the `sync` driver, this add-on won't have any effect.
  

## Installation


From a standard Statamic V3 site, you can run:

`composer require daynnnnn/statamic-swr-cache`

Then you'll just need to add the stale while revalidate strategy to your static cache config:

```

'strategies' => [
    ...
    'swr' => [
        'driver' => 'swr',
        'expiry' => '60',
        'stale' => '5',
    ],
],

```

### How it works

In the above example, cached pages will stay in the cache for 60 minutes (expiry), but if the cached page is accessed when the cache is over 5 minutes old (stale), a background job will run to update the cache, but the currently cached page will still be served.

Other than this, the cache will stay the same as the application cache.
