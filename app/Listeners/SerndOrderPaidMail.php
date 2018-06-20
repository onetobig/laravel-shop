<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Notifications\OrderPaidNotificattion;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SerndOrderPaidMail
{
    public function handle(OrderPaid $event)
    {
        $order = $event->getOrder();

        $order->user->notify(new OrderPaidNotificattion($order));
    }
}
