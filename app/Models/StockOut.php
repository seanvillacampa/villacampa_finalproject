<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class StockOut extends Model
{
    protected $fillable = [
        'item_id', 'user_id', 'animal_id',
        'reason', 'quantity', 'notes', 'date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'date'     => 'date',
    ];

    public function item()  { return $this->belongsTo(Item::class); }
    public function user()  { return $this->belongsTo(User::class); }
    public function animal(){ return $this->belongsTo(Animal::class); }
}