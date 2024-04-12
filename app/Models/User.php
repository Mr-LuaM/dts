<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;



class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id', 'email', 'password', 'role', 'lname', 'fname', 'mname',
        'birthdate', 'sex', 'contact_number', 'address', 'fcm_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationship: User has many audit logs
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function otps()
{
    return $this->hasMany(UserOtp::class);
}
public function events()
{
    return $this->hasMany(Event::class, 'organizer_id');
}

public function tickets()
{
    return $this->hasMany(Ticket::class);
}

public function transactions()
{
    return $this->hasMany(Transaction::class);
}

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmail);
    }
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
    

    // Add any additional methods or relationships below...
}
