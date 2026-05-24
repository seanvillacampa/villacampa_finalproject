<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'sku', 'category_id', 'unit_id',
        'description', 'reorder_level',
    ];

    protected $guarded = ['current_stock'];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'reorder_level' => 'decimal:2',
    ];

    public function setCurrentStockAttribute($value)
    {
        throw new \Exception('Stock cannot be set manually. Use stock movements.');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_items')
                    ->withPivot('unit_price')
                    ->withTimestamps();
    }

    public function supplierItems()
    {
        return $this->hasMany(SupplierItem::class);
    }

    public function purchaseHistoryItems()
    {
        return $this->hasMany(PurchaseHistoryItem::class);
    }

    public function animalFeedLogs()
    {
        return $this->hasMany(AnimalFeedLog::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->reorder_level;
    }

    public function getStatusAttribute(): string
    {
        if ($this->current_stock == 0)                        return 'no stock';
        if ($this->current_stock <= $this->reorder_level)    return 'low stock';
        return 'enough stock';
    }
}