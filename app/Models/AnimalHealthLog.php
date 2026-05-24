<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AnimalHealthLog extends Model
{
    protected $fillable = [
    'animal_id', 'user_id', 'item_id', 'type', 'description',
    'medicine_used', 'dosage', 'dosage_quantity',
    'administered_by', 'next_schedule_date', 'log_date',
    ];

    protected $casts = [
        'log_date'           => 'date',
        'next_schedule_date' => 'date',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function item()
{
    return $this->belongsTo(\App\Models\Item::class);
}
}