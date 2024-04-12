<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'token', 'created_at',
    ];

    // Relationship back to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
