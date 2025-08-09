<?php

namespace App\Services;

use App\Models\Material;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\ProductStockMovement;

class InventoryService
{
    public function postMaterialIn(int $materialId, int $qty, int $userId, string $desc = null): void
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Quantity must be positive for IN movement.');
        }
        StockMovement::create([
            'material_id' => $materialId,
            'user_id' => $userId,
            'quantity' => $qty,
            'type' => 'in',
            'description' => $desc,
            'created_at' => now(),
        ]);
        Material::whereKey($materialId)->increment('stock', $qty);
    }

    public function postMaterialOut(int $materialId, int $qty, int $userId, string $desc = null): void
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Quantity must be positive for OUT movement.');
        }
        StockMovement::create([
            'material_id' => $materialId,
            'user_id' => $userId,
            'quantity' => -$qty,
            'type' => 'out',
            'description' => $desc,
            'created_at' => now(),
        ]);
        Material::whereKey($materialId)->decrement('stock', $qty);
    }

    public function postProductOut(int $productId, int $qty, ?string $refType = null, ?int $refId = null, ?string $notes = null): void
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Quantity must be positive for OUT movement.');
        }
        ProductStockMovement::create([
            'product_id' => $productId,
            'type' => 'out',
            'quantity' => $qty,
            'ref_type' => $refType,
            'ref_id' => $refId,
            'notes' => $notes,
            'created_at' => now(),
        ]);
        Product::whereKey($productId)->decrement('stock', $qty);
    }

    public function postProductIn(int $productId, int $qty, ?string $refType = null, ?int $refId = null, ?string $notes = null): void
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Quantity must be positive for IN movement.');
        }
        ProductStockMovement::create([
            'product_id' => $productId,
            'type' => 'in',
            'quantity' => $qty,
            'ref_type' => $refType,
            'ref_id' => $refId,
            'notes' => $notes,
            'created_at' => now(),
        ]);
        Product::whereKey($productId)->increment('stock', $qty);
    }
}
