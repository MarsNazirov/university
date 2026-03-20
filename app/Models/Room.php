<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    const STATUS_AVAILABLE = 'свободная';
    const STATUS_OCCUPIED = 'занятая';

    protected $fillable = [
        'number',
        'floor',
        'type',
        'price',
        'beds_count',
        'status',
        'description',
        'photo'
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function scopeAvailable($query)
    {
        $query->where('status', '=', 'available');
    }

    public function isAvailable()
    {
        if ($this->status !== 'available') {
            return false;
        } else {
            return true;
        }
    }
}
