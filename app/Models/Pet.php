<?php

namespace App\Models;

use App\Models\Pet\Breed;
use App\Models\Pet\Specie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'user_id',
        'breed_id',
        'specie_id',
    ];

    public function specie(): BelongsTo
    {
        return $this->belongsTo(Specie::class, 'specie_id', 'id');
    }

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class, 'breed_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
