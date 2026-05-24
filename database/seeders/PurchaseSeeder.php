<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\PurchaseHistory;
use App\Models\PurchaseHistoryItem;
use App\Models\User;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin     = User::first();
        $agrivet   = Supplier::where('name', 'AgriVet Supply Davao')->first();
        $feliciano = Supplier::where('name', 'Feliciano Feeds & Trading')->first();
        $bmeg      = Supplier::where('name', 'B-MEG Davao Dealer')->first();
        $vetline   = Supplier::where('name', 'Vetline Animal Health')->first();

        $purchases = [
            [
                'supplier'      => $bmeg,
                'reference'     => 'PO-2025-001',
                'purchase_date' => '2025-03-10',
                'status'        => 'received',
                'items' => [
                    ['sku' => 'CF-001', 'qty' => 200, 'price' => 42.00],
                    ['sku' => 'CF-002', 'qty' => 150, 'price' => 45.00],
                    ['sku' => 'CF-003', 'qty' => 20,  'price' => 980.00],
                    ['sku' => 'PF-002', 'qty' => 15,  'price' => 1050.00],
                    ['sku' => 'PF-003', 'qty' => 10,  'price' => 1080.00],
                ],
            ],
            [
                'supplier'      => $feliciano,
                'reference'     => 'PO-2025-002',
                'purchase_date' => '2025-03-18',
                'status'        => 'received',
                'items' => [
                    ['sku' => 'PF-001', 'qty' => 100, 'price' => 38.00],
                    ['sku' => 'PF-004', 'qty' => 8,   'price' => 1100.00],
                    ['sku' => 'CTF-001','qty' => 5,   'price' => 1250.00],
                    ['sku' => 'CTF-002','qty' => 10,  'price' => 450.00],
                ],
            ],
            [
                'supplier'      => $vetline,
                'reference'     => 'PO-2025-003',
                'purchase_date' => '2025-04-02',
                'status'        => 'received',
                'items' => [
                    ['sku' => 'CM-001', 'qty' => 10,  'price' => 85.00],
                    ['sku' => 'CM-002', 'qty' => 10,  'price' => 90.00],
                    ['sku' => 'CM-003', 'qty' => 200, 'price' => 8.50],
                    ['sku' => 'PM-001', 'qty' => 500, 'price' => 1.80],
                    ['sku' => 'PM-002', 'qty' => 300, 'price' => 2.20],
                    ['sku' => 'PM-003', 'qty' => 8,   'price' => 120.00],
                    ['sku' => 'CTM-001','qty' => 6,   'price' => 250.00],
                    ['sku' => 'CTM-002','qty' => 5,   'price' => 480.00],
                    ['sku' => 'CTM-003','qty' => 10,  'price' => 95.00],
                ],
            ],
            [
                'supplier'      => $agrivet,
                'reference'     => 'PO-2025-004',
                'purchase_date' => '2025-04-15',
                'status'        => 'received',
                'items' => [
                    ['sku' => 'SUP-001','qty' => 10,  'price' => 180.00],
                    ['sku' => 'SUP-002','qty' => 25,  'price' => 65.00],
                    ['sku' => 'DIS-001','qty' => 5,   'price' => 890.00],
                    ['sku' => 'DIS-002','qty' => 10,  'price' => 320.00],
                    ['sku' => 'CM-004', 'qty' => 500, 'price' => 0.95],
                    ['sku' => 'PM-004', 'qty' => 100, 'price' => 5.50],
                ],
            ],
        ];

        foreach ($purchases as $p) {
            $total = collect($p['items'])->sum(fn($i) => $i['qty'] * $i['price']);

            $purchase = PurchaseHistory::firstOrCreate(
                ['reference_number' => $p['reference']],
                [
                    'supplier_id'   => $p['supplier']->id,
                    'user_id'       => $admin->id,
                    'purchase_date' => $p['purchase_date'],
                    'total_amount'  => $total,
                    'status'        => $p['status'],
                ]
            );

            // Only add stock if this purchase was just created (not already seeded)
            $wasRecentlyCreated = $purchase->wasRecentlyCreated;

            foreach ($p['items'] as $line) {
                $item = Item::where('sku', $line['sku'])->first();

                PurchaseHistoryItem::firstOrCreate(
                    ['purchase_history_id' => $purchase->id, 'item_id' => $item->id],
                    [
                        'quantity'   => $line['qty'],
                        'unit_price' => $line['price'],
                        'subtotal'   => $line['qty'] * $line['price'],
                    ]
                );

                // Only increment stock if the purchase record was newly inserted
                if ($wasRecentlyCreated) {
                    $item->increment('current_stock', $line['qty']);
                }
            }
        }
    }
}