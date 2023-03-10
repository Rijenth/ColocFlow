<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'access_key',
        'monthly_rent',
        'max_roommates',
        'user_id',
    ];

    protected $hidden = [
        'access_key',
        'created_at',
        'id',
        'updated_at',
        'user_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function roommates()
    {
        return $this->hasMany(User::class);
    }
}
