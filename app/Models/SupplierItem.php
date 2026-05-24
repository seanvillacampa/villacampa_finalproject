<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SupplierItem extends Model
{
    protected $fillable = ['supplier_id', 'item_id', 'unit_price'];

    protected $casts = ['unit_price' => 'decimal:2'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}