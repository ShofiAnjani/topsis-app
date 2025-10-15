<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Criterion extends Model
{
    use HasFactory;
    protected $fillable = ['name','type','weight'];

    public function scores(): HasMany
    {
        return $this->hasMany(related: score::class);
    }
}