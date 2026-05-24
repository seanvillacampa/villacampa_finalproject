<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AnimalFeedLog extends Model
{
    protected $fillable = [
        'animal_id', 'item_id', 'user_id',
        'quantity', 'feed_date',
    ];

    protected $casts = [
        'quantity'  => 'decimal:2',
        'feed_date' => 'date',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}