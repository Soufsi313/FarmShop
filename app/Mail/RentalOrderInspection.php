<?php

namespace App\Mail;

use App\Models\OrderLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentalOrderInspection extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public OrderLocation $orderLocation;

    /**
     * Create a new message instance.
     */
    public function __construct(OrderLocation $orderLocation)
    {
        $this->orderLocation = $orderLocation->load(['orderItemLocations.product']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rapport d\'inspection de votre location #' . $this->orderLocation->order_number,
            from: config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $inspectionSummary = $this->getInspectionSummary();
        
        return new Content(
            markdown: 'emails.rental-order-inspection',
            with: [
                'orderLocation' => $this->orderLocation,
                'user' => $this->orderLocation->user,
                'items' => $this->orderLocation->orderItemLocations,
                'inspectionSummary' => $inspectionSummary,
                'totalPenalties' => $this->orderLocation->penalty_amount,
                'depositRefund' => $this->orderLocation->deposit_amount - $this->orderLocation->penalty_amount,
                'hasGoodItems' => $inspectionSummary['good_items'] > 0,
                'hasDamagedItems' => $inspectionSummary['damaged_items'] > 0,
                'hasLostItems' => $inspectionSummary['lost_items'] > 0,
                'hasPenalties' => $this->orderLocation->penalty_amount > 0,
            ],
        );
    }

    /**
     * Obtenir un résumé de l'inspection
     */
    private function getInspectionSummary(): array
    {
        $summary = [
            'total_items' => 0,
            'good_items' => 0,
            'damaged_items' => 0,
            'lost_items' => 0,
            'damage_costs' => 0,
            'late_fees' => 0,
        ];

        foreach ($this->orderLocation->orderItemLocations as $item) {
            $summary['total_items'] += $item->quantity;
            
            if ($item->return_condition === 'good') {
                $summary['good_items'] += $item->quantity;
            } elseif ($item->return_condition === 'damaged') {
                $summary['damaged_items'] += $item->quantity;
                $summary['damage_costs'] += $item->damage_cost;
            } elseif ($item->return_condition === 'lost') {
                $summary['lost_items'] += $item->quantity;
            }
            
            $summary['late_fees'] += $item->penalty_amount - $item->damage_cost;
        }

        return $summary;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
