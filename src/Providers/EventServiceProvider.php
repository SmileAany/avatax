<?php

namespace Smbear\Avatax\Providers;

use Smbear\Avatax\Events\SaveDataEvent;
use Smbear\Avatax\Listeners\SaveDataListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SaveDataEvent::class => [
            SaveDataListener::class
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}