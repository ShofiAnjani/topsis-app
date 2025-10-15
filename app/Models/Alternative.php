<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alternative extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address'];

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function result()
    {
        return $this->hasOne(Result::class);
    }
}