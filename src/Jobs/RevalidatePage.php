<?php

namespace Daynnnnn\Statamic\SwrCache\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;

class RevalidatePage implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var string
     */
    public $url;

    /**
     * @param string $url
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Make internal request to revalidate cache.
     * 
     * @return void
     */
    public function handle()
    {
        config(['statamic.static_swr.revalidate' => true]);
        $request = Request::create($this->url, 'GET');
        app()->handle($request);
        config(['statamic.static_swr.revalidate' => false]);
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return md5($this->url);
    }
}
