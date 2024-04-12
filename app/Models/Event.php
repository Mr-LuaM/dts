<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'image',
        'location',
        'start_time',
        'end_time',
        'organizer_id',
    ];
    protected $dates = ['start_time', 'end_time'];


    public function organizer()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
 // In your Event model
protected static function newFactory()
{
    return \Database\Factories\EventFactory::new();
}

}
