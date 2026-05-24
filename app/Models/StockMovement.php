<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'item_id', 'user_id', 'purchase_history_id',
        'type', 'quantity', 'lot_number',
        'expiry_date', 'reason',
    ];

    protected $casts = [
        'quantity'    => 'decimal:2',
        'expiry_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseHistory()
    {
        return $this->belongsTo(PurchaseHistory::class);
    }
}