<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machinery extends Model
{
    use HasFactory;

    protected $table = 'machineries'; // ✅ Explicitly set the table name

    protected $fillable = [
        'name',
        'model',
        'reg_num', // ✅ Added 'reg_num' field to fix the error
        'status',
    ];

    /**
     * ✅ Relationship: One machinery can have many usage records
     */
    public function usageRecords(): HasMany
    {
        return $this->hasMany(UsageRecord::class);
    }

    /**
     * ✅ Helper function to check if machinery is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'Operational';
    }
}