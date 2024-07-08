<?php

namespace App\Events;

use App\Models\Billing;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessedBill
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $billing;

    public function __construct(Billing $billing)
    {
        $this->billing = $billing;
    }
}
