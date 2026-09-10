<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemActivity extends Model
{
    protected $table = 'system_activities';
    
    protected $fillable = [
        'type', 'action', 'entity_type', 'entity_id', 'data',
        'user_id', 'user_name', 'priority', 'action_links', 
        'is_read', 'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'action_links' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope pour les notifications non lues
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope pour les notifications critiques
     */
    public function scopeCritical($query)
    {
        return $query->where('priority', 'critical');
    }

    /**
     * Scope pour un type spécifique
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Marque comme lu
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Vérifie si la notification est lue
     */
    public function isRead(): bool
    {
        return (bool) $this->is_read;
    }

    /**
     * Récupère le message formaté
     */
    public function getFormattedMessage(): string
    {
        $data = $this->data ?? [];
        
        return match($this->type) {
            'ticket_closed' => "Ticket {$data['ticket_number']} clôturé - Client: {$data['customer_name']}",
            'low_stock' => "Stock bas: {$data['product_name']} ({$data['current_quantity']} unités)",
            'critical_part' => "Pièce critique: {$data['part_name']} (stock: {$data['current_stock']})",
            'ticket_assigned' => "Nouveau ticket assigné: {$data['ticket_number']}",
            default => $data['message'] ?? 'Nouvelle activité',
        };
    }

    /**
     * Récupère le titre formaté
     */
    public function getFormattedTitle(): string
    {
        return match($this->type) {
            'ticket_closed' => 'Ticket clôturé',
            'ticket_assigned' => 'Nouveau ticket',
            'ticket_urgent' => 'Ticket urgent',
            'low_stock' => 'Stock bas',
            'critical_part' => 'Pièce critique',
            'invoice_created' => 'Nouvelle facture',
            'purchase_order_created' => 'Bon de commande',
            default => 'Notification',
        };
    }

    /**
     * Récupère l'icône
     */
    public function getIcon(): string
    {
        return match($this->type) {
            'ticket_closed' => 'bi-check-circle',
            'ticket_assigned' => 'bi-person-plus',
            'ticket_urgent' => 'bi-alarm',
            'low_stock' => 'bi-exclamation-triangle',
            'critical_part' => 'bi-puzzle',
            'invoice_created' => 'bi-receipt',
            'purchase_order_created' => 'bi-cart-check',
            default => 'bi-bell',
        };
    }

    /**
     * Récupère la couleur de l'icône
     */
    public function getIconColor(): string
    {
        return match($this->type) {
            'ticket_closed' => '#16a34a',
            'ticket_assigned' => '#0891b2',
            'ticket_urgent', 'critical_part' => '#dc2626',
            'low_stock' => '#f07d00',
            default => '#1a7a3c',
        };
    }
}