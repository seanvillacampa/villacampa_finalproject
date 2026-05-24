<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PurchaseHistoryItem extends Model
{
    protected $fillable = [
        'purchase_history_id', 'item_id',
        'quantity', 'unit_price', 'subtotal',
    ];

    protected $casts = [
        'quantity'  => 'decimal:2',
        'unit_price'=> 'decimal:2',
        'subtotal'  => 'decimal:2',
    ];

    public function purchaseHistory()
    {
        return $this->belongsTo(PurchaseHistory::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getSubtotalComputedAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }
}