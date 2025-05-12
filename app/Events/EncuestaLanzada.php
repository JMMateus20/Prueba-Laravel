<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EncuestaLanzada implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $encuesta;

    public function __construct($encuesta)
    {
        $this->encuesta=$encuesta;
    }

    
    public function broadcastOn()
    {
        Log::info("Broadcast en canal 'encuestas'");
        return new Channel('encuestas');
    }

    public function broadcastAs()
    {
        return 'EncuestaLanzada';
    }
}
