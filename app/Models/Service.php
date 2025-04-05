<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image', 'id_type'];

    public function typeService(): BelongsTo
    {
        return $this->belongsTo(TypeService::class, 'id_type', 'id');
    }
}
