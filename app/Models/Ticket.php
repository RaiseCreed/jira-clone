<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $attributes = [
        'ticket_status_id' => 1, // Z readme dominika
    ];
    
    protected $primaryKey = 'TicketID';
    protected $fillable = [
        'ticket_category_id',
        'ticket_priority_id',
        'ticket_status_id',
        'title',
        'date',
        'deadline',
        'date_end',
        'content'
    ];

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function priority()
    {
        return $this->belongsTo(TicketPriority::class, 'ticket_priority_id');
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'ticket_status_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class, 'user_ticket_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tickets', 'ticket_id', 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'ticket_id');
    }
}
