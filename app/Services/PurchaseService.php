<?php

namespace App\Services;

use App\Models\GoodsReceipt;
use App\Models\PurchaseOrderItem;

class PurchaseService
{
    public function __construct(
        protected InventoryService $inventory,
        protected FinanceService $finance
    ) {}

    public function receiveGoods(GoodsReceipt $gr): void
    {
        foreach ($gr->items as $item) {
            /** @var PurchaseOrderItem $poi */
            $poi = $item->poItem;

            if ($poi->item_type === 'material') {
                $this->inventory->postMaterialIn($poi->item_id, (int)$item->qty_received, $gr->received_by, "GR {$gr->gr_number}");
            } else {
                $this->inventory->postProductIn($poi->item_id, (int)$item->qty_received, 'purchase', $gr->id, "GR {$gr->gr_number}");
            }
        }

        // Journal template placeholder
        // $this->finance->postJournal('Goods Receipt '.$gr->gr_number, [...]);
    }
}
