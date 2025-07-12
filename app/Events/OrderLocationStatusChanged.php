<?php

namespace App\Events;

use App\Models\OrderLocation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderLocationStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public OrderLocation $orderLocation;
    public string $oldStatus;
    public string $newStatus;
    public ?string $reason;

    /**
     * Create a new event instance.
     */
    public function __construct(OrderLocation $orderLocation, string $oldStatus, string $newStatus, ?string $reason = null)
    {
        $this->orderLocation = $orderLocation;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->reason = $reason;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('order-location.' . $this->orderLocation->id),
        ];
    }
}
