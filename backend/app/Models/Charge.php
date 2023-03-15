<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Charge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'colocation_id',
        'key',
    ];

    protected $hidden = [
        'created_at',
        'colocation_id',
        'id',
        'updated_at',
    ];

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
