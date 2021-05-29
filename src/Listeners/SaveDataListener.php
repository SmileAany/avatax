<?php

namespace Smbear\Avatax\Listeners;

use Smbear\Avatax\Events\SaveDataEvent;
use Smbear\Avatax\Models\AvataxRecords;

class SaveDataListener
{
    public function handle(SaveDataEvent $event)
    {
        if ($event->type == 'SalesInvoice'){
            AvataxRecords::create($event->data);
        }
    }
}