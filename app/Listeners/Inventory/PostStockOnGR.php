<?php

namespace App\Listeners\Inventory;

use App\Events\GoodsReceiptCreated;
use App\Models\PurchaseOrderItem;
use App\Services\InventoryService;

class PostStockOnGR
{
    public function __construct(private readonly InventoryService $inventory) {}

    public function handle(GoodsReceiptCreated $event): void
    {
        $gr = $event->goodsReceipt->load('items.poItem');

        foreach ($gr->items as $item) {
            /** @var PurchaseOrderItem $poi */
            $poi = $item->poItem;
            if (!$poi) {
                continue;
            }

            if ($poi->item_type === 'material') {
                $this->inventory->postMaterialIn($poi->item_id, (int) $item->qty_received, $gr->received_by, "GR {$gr->gr_number}");
            } else {
                $this->inventory->postProductIn($poi->item_id, (int) $item->qty_received, 'purchase', $gr->id, "GR {$gr->gr_number}");
            }
        }
    }
}
