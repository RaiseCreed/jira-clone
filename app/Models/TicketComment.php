<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    use HasFactory;

    protected $primaryKey = 'TicketCommentID';
    protected $fillable = ['user_ticket_id', 'user', 'comment', 'date'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'user_ticket_id');
    }
}
