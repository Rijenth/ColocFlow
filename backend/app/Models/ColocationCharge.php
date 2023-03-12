<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColocationCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "amount",
        "colocation_id",
        "key",
    ];

    protected $hidden = [
        "created_at",
        "id",
        "updated_at",
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }
}
