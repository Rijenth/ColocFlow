<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Charge extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'float',
        'amount_affected' => 'float',
    ];

    protected $fillable = [
        'name',
        'amount',
        'amount_affected',
        'colocation_id',
        'key',
    ];

    protected $hidden = [
        'created_at',
        'colocation_id',
        'id',
        'updated_at',
    ];

    protected static function booted()
    {
        static::saving(function ($charge) {
            if (! $charge->name) {
                $charge->name = ucwords(explode('_', $charge['key'])[0]);
            }

            if (! $charge->amount_affected) {
                $charge->amount_affected = 0;
            }
        });
    }

    public function colocation(): BelongsTo
    {
        return $this->belongsTo(Colocation::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'colocation_charges_users')
            ->withPivot('amount');
    }
}
