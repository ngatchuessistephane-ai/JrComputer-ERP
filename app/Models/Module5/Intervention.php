<?php

namespace App\Models\Module5;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $table = 'interventions';
    
    protected $fillable = [
        'ticket_id', 
        'technician_id',
        'description',        // Description de l'intervention
        'technical_report',   // Rapport technique détaillé
        'duration_minutes', 
        'client_signature', 
        'synced'
    ];

    protected $casts = [
        'synced' => 'boolean',
        'duration_minutes' => 'integer',
    ];

    public function ticket()
    {
        return $this->belongsTo(SavTicket::class, 'ticket_id');
    }

    public function technician()
    {
        return $this->belongsTo(\App\Models\User::class, 'technician_id');
    }
}