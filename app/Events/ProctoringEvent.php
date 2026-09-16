<?php  // app/Events/ProctoringEvent.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

class ProctoringEvent implements ShouldBroadcast
{
    use SerializesModels;

    public array $data;
    public string $eventType;

    public function __construct(string $eventType, array $data)
    {
        $this->eventType = $eventType;
        $this->data = $data;
    }

    // Saluran khusus pengawas
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('proctoring.dashboard');
    }

    public function broadcastAs(): string
    {
        return 'proctoring.update';
    }
}