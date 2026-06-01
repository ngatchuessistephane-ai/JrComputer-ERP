<?php

namespace App\Models\Module5;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $table = 'interventions';
    protected $fillable = [
        'ticket_id', 'technical_report', 'duration_minutes',
        'client_signature', 'synced'
    ];

    protected $casts = [
        'synced' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(SavTicket::class, 'ticket_id');
    }
}