<?php

namespace App\Observers;

use App\Models\Module2\PurchaseOrder;

class PurchaseOrderObserver
{
    /**
     * Handle the PurchaseOrder "created" event.
     */
    public function created(PurchaseOrder $purchaseOrder): void
    {
        // Pas d'action à la création
    }

    /**
     * Handle the PurchaseOrder "updated" event.
     */
    public function updated(PurchaseOrder $purchaseOrder): void
    {
        // Si le statut change vers "received", mettre à jour le total du fournisseur
        if ($purchaseOrder->wasChanged('status') && $purchaseOrder->status === PurchaseOrder::STATUS_RECEIVED) {
            $purchaseOrder->updateSupplierTotalPurchased();
        }
        
        // Si le statut change depuis "received", recalculer aussi
        if ($purchaseOrder->wasChanged('status') && $purchaseOrder->getOriginal('status') === PurchaseOrder::STATUS_RECEIVED) {
            $purchaseOrder->updateSupplierTotalPurchased();
        }
    }

    /**
     * Handle the PurchaseOrder "deleted" event.
     */
    public function deleted(PurchaseOrder $purchaseOrder): void
    {
        if ($purchaseOrder->status === PurchaseOrder::STATUS_RECEIVED) {
            $purchaseOrder->updateSupplierTotalPurchased();
        }
    }

    /**
     * Handle the PurchaseOrder "restored" event.
     */
    public function restored(PurchaseOrder $purchaseOrder): void
    {
        if ($purchaseOrder->status === PurchaseOrder::STATUS_RECEIVED) {
            $purchaseOrder->updateSupplierTotalPurchased();
        }
    }
}