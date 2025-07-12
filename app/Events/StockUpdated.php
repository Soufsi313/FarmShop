<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Product $product;
    public int $previousQuantity;
    public int $newQuantity;
    public string $changeType;

    /**
     * Create a new event instance.
     */
    public function __construct(Product $product, int $previousQuantity, int $newQuantity, string $changeType = 'manual')
    {
        $this->product = $product;
        $this->previousQuantity = $previousQuantity;
        $this->newQuantity = $newQuantity;
        $this->changeType = $changeType;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-dashboard'),
            new PrivateChannel('stock-alerts')
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_sku' => $this->product->sku,
            'previous_quantity' => $this->previousQuantity,
            'new_quantity' => $this->newQuantity,
            'change_type' => $this->changeType,
            'stock_status' => $this->product->stock_status,
            'is_critical' => $this->product->is_critical_stock,
            'is_out_of_stock' => $this->product->is_out_of_stock,
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'stock.updated';
    }
}
