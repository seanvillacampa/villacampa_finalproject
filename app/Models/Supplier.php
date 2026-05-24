<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name', 'contact_person', 'email', 'phone',
        'street_address', 'barangay_address',
        'city_address', 'province_address', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'supplier_items')
                    ->withPivot('unit_price')
                    ->withTimestamps();
    }

    public function supplierItems()
    {
        return $this->hasMany(SupplierItem::class);
    }

    public function purchaseHistories()
    {
        return $this->hasMany(PurchaseHistory::class);
    }

    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->street_address,
            $this->barangay_address,
            $this->city_address,
            $this->province_address,
        ]));
    }
}