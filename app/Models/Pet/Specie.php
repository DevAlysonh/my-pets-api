<?php

namespace App\Models\Pet;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class, 'specie_id', 'id');
    }

    public function breeds(): HasMany
    {
        return $this->hasMany(Breed::class, 'breed_id', 'id');
    }
}
