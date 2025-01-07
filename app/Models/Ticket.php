<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    
    protected $casts = [
        'deadline' => 'datetime'
    ];
    
    protected $primaryKey = 'id';
    protected $fillable = [
        'ticket_category_id',
        'ticket_priority_id',
        'ticket_status_id',
        'owner_id',
        'worker_id',
        'title',
        'date',
        'deadline',
        'date_end',
        'content',
        'description',
        'assigned_to', 
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
        return $this->hasMany(TicketComment::class, 'ticket_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'author');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'ticket_id');
    }
    public function assignedUser()

    {
    return $this->belongsTo(User::class, 'assigned_to');
    }
}
