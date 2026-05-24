<?php

// app/Helpers/CodeGenerator.php

namespace App\Helpers;

use App\Models\Item;
use App\Models\Animal;
use App\Models\PurchaseHistory;
use App\Models\StockMovement;

class CodeGenerator
{
    // ── SKU ──────────────────────────────────────────────────
    // Format: AGR-[CATEGORY_CODE]-[PADDED_ID]
    // Example: AGR-FERT-00042
    public static function sku(string $categoryName, int $itemId): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $categoryName), 0, 4));
        return 'AGR-' . $prefix . '-' . str_pad($itemId, 5, '0', STR_PAD_LEFT);
    }

    // ── REFERENCE NUMBER ─────────────────────────────────────
    // Format: PO-[YEAR]-[PADDED_COUNT]
    // Example: PO-2026-00015
    public static function referenceNumber(): string
    {
        $year  = now()->year;
        $count = PurchaseHistory::whereYear('created_at', $year)->count() + 1;
        return 'PO-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    // ── TAG NUMBER ───────────────────────────────────────────
    // Format: [SPECIES_CODE]-[YEAR]-[PADDED_ID]
    // Example: HOG-2026-00003
    public static function tagNumber(string $species, int $animalId): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $species), 0, 3));
        return $prefix . '-' . now()->year . '-' . str_pad($animalId, 5, '0', STR_PAD_LEFT);
    }

    // ── LOT NUMBER ───────────────────────────────────────────
    // Format: LOT-[YYYYMMDD]-[PADDED_DAILY_COUNT]
    // Example: LOT-20260504-003
    public static function lotNumber(): string
    {
        $today = now()->format('Ymd');
        $count = StockMovement::whereDate('created_at', today())
                     ->where('type', 'stock_in')
                     ->count() + 1;
        return 'LOT-' . $today . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
