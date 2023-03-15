<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function roommates(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }
}
