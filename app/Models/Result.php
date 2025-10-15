<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // ✅ tambahkan ini aja

class Result extends Model
{
    use HasFactory;

    protected $fillable = ['alternative_id', 'preference_score', 'rank'];

    public function alternative(): BelongsTo
    {
        // ✅ tetap pakai struktur kamu, hanya hapus named argument agar sesuai standar PHP 8
        return $this->belongsTo(Alternative::class);
    }
}