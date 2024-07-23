<?php

namespace App\Models\Pet;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Breed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specie_id'
    ];

    public function specie(): BelongsTo
    {
        return $this->belongsTo(Specie::class, 'specie_id', 'id');
    }

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class, 'breed_id', 'id');
    }
}
