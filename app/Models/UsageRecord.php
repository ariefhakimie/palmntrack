<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsageRecord extends Model
{
    use HasFactory;

    protected $table = 'usage_records';

    protected $fillable = ['user_id', 'machinery_id', 'equipment_id', 'usage_timestamps'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function machinery()
    {
        return $this->belongsTo(Machinery::class, 'machinery_id', 'id');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id', 'id');
    }
}