<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = ['ticket_id', 'blob', 'date', 'file_path', 'file_name'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}    