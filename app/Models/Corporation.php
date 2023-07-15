<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Corporation extends Model
{
    use HasFactory;

    protected $table = 'corporation';
    protected $fillable = [
        '_id',
        'name',
        'type',
    ];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class, 'parent_id', '_id');
    }
}
