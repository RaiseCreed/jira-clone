<?php

namespace App\Models;
//tu odjebałem cos bo nadtworzyło cos do tego modelu ale usunałem 
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $primaryKey = 'UserID';
    protected $fillable = ['role_id', 'email', 'name', 's_name', 'login', 'pass'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'user_tickets', 'user_id', 'ticket_id');
    }
}
