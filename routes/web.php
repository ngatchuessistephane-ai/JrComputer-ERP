<?php

use App\Http\Livewire\Module1\ProductIndex;
use App\Http\Livewire\Module2\SupplierIndex;
use App\Http\Livewire\Module2\PurchaseOrderIndex;
use App\Exports\ProductsPdfExport;
use App\Exports\SuppliersPdfExport;
use App\Exports\PurchaseOrdersPdfExport;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // ✅ AJOUTER CETTE LIGNE

// Module 3 – Ventes
use App\Http\Livewire\Module3\CustomerIndex;
use App\Http\Livewire\Module3\QuoteIndex;
use App\Http\Livewire\Module3\QuoteForm;
use App\Http\Livewire\Module3\InvoiceIndex;
use App\Http\Livewire\Module3\PosIndex;
use App\Http\Livewire\Module3\QuoteShow;
use App\Exports\InvoicesPdfExport;
use App\Http\Controllers\Module3\InvoicePdfController;
use App\Exports\TicketPdfExport;
use App\Exports\AnalyticsPdfExport;
use App\Exports\QuotePdfExport; // ✅ AJOUTER CETTE LIGNE

// Espace Technicien SAV (Web)
use App\Http\Controllers\Technician\TicketController;

use App\Http\Controllers\NotificationPageController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Routes d'authentification (générées par Breeze)
require __DIR__.'/auth.php';

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    
    // ============================================================
    // DASHBOARD & PROFIL
    // ============================================================
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('can:view analytics');
    Route::get('/notifications', [NotificationPageController::class, 'index'])->name('notifications.index');
    
    Route::get('/profile', \App\Http\Livewire\Profile::class)->name('profile');
    
    // ============================================================
    // MODULE 1 : PRODUITS & STOCK
    // ============================================================
    
    Route::get('/produits', ProductIndex::class)->name('module1.products.index');
    
    // ============================================================
    // MODULE 2 : ACHATS & FOURNISSEURS
    // ============================================================
    
    Route::get('/fournisseurs', SupplierIndex::class)->name('module2.suppliers.index');
    Route::get('/achats/bons-commande', PurchaseOrderIndex::class)->name('module2.purchase-orders.index');
    
    // ============================================================
    // MODULE 3 : VENTES & CRM
    // ============================================================
    
    Route::get('/clients', CustomerIndex::class)->name('module3.customers.index');
    
    // ✅ IMPORTANT : Routes spécifiques AVANT les routes avec paramètre
    Route::get('/devis/creer', QuoteForm::class)->name('module3.quotes.create');
    Route::get('/devis/{id}/modifier', QuoteForm::class)->name('module3.quotes.edit');
    
    // ✅ Route avec paramètre APRÈS les routes spécifiques
    Route::get('/devis/{id}', QuoteShow::class)->name('module3.quotes.show');
    
    // ✅ Route index (liste) APRÈS toutes les routes spécifiques
    Route::get('/devis', QuoteIndex::class)->name('module3.quotes.index');
    
    // Factures
    Route::get('/factures', InvoiceIndex::class)->name('module3.invoices.index');
    Route::get('/factures/creer', \App\Http\Livewire\Module3\InvoiceForm::class)->name('module3.invoices.create');
    Route::get('/factures/{id}/modifier', \App\Http\Livewire\Module3\InvoiceForm::class)->name('module3.invoices.edit');
    Route::get('/factures/{id}', \App\Http\Livewire\Module3\InvoiceShow::class)->name('module3.invoices.show');
    Route::get('/factures/{id}/pdf', function ($id) {
        $invoice = \App\Models\Module3\Invoice::findOrFail($id);
        return (new \App\Exports\InvoicesPdfExport())->generate($invoice);
    })->name('module3.invoices.pdf');
    
    // POS
    Route::get('/pos', PosIndex::class)->name('module3.pos.index');
    
    
    // ============================================================
    // MODULE 4 : SERVICES APRÈS-VENTE (SAV)
    // ============================================================
    
    Route::prefix('sav')->name('module5.')->group(function () {
        Route::get('/tickets', \App\Http\Livewire\Module5\TicketIndex::class)->name('tickets.index');
        Route::get('/tickets/create', \App\Http\Livewire\Module5\TicketForm::class)->name('tickets.create');
        Route::get('/tickets/{id}', \App\Http\Livewire\Module5\TicketShow::class)->name('tickets.show');
        Route::get('/tickets/{id}/edit', \App\Http\Livewire\Module5\TicketForm::class)->name('tickets.edit');
        Route::get('/parts', \App\Http\Livewire\Module5\SparePartIndex::class)->name('parts.index');
    });
    
    // ============================================================
    // ESPACE TECHNICIEN SAV (WEB)
    // ============================================================
    Route::prefix('technicien')->name('technician.')->middleware(['auth', \App\Http\Middleware\CheckTechnicianRole::class])->group(function () {
        Route::get('/dashboard', [TicketController::class, 'dashboard'])->name('dashboard');
        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
        Route::get('/tickets/{id}/close', [TicketController::class, 'closeForm'])->name('tickets.close-form');
        Route::post('/tickets/{id}/close', [TicketController::class, 'close'])->name('tickets.close');
        Route::patch('/tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
    });
    
    // ============================================================
    // GESTION DES UTILISATEURS (Admin uniquement)
    // ============================================================
    
    Route::get('/utilisateurs', \App\Http\Livewire\UserIndex::class)->name('users.index');
    
    // ============================================================
    // EXPORTS PDF - MODULE 1 (Produits)
    // ============================================================
    
    Route::get('/export/produits/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->input('date_from'),
            'date_to'   => $request->input('date_to'),
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
            'category'  => $request->input('category'),
            'supplier'  => $request->input('supplier'),
            'stock_status' => $request->input('stock_status'),
        ];
        return (new ProductsPdfExport($filters))->generate();
    })->name('export.products.pdf');
    
    // ============================================================
    // EXPORTS PDF - MODULE 2 (Achats)
    // ============================================================
    
    Route::get('/export/fournisseurs/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->input('date_from'),
            'date_to'   => $request->input('date_to'),
            'name'      => $request->input('name'),
            'payment_terms_min' => $request->input('payment_terms_min'),
            'payment_terms_max' => $request->input('payment_terms_max'),
        ];
        return (new SuppliersPdfExport($filters))->generate();
    })->name('export.suppliers.pdf');
    
    Route::get('/export/bons-commande/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->input('date_from'),
            'date_to'   => $request->input('date_to'),
            'status'    => $request->input('status'),
            'supplier_id' => $request->input('supplier_id'),
            'total_min' => $request->input('total_min'),
            'total_max' => $request->input('total_max'),
        ];
        return (new PurchaseOrdersPdfExport($filters))->generate();
    })->name('export.purchase-orders.pdf');
    
    // ============================================================
    // EXPORTS PDF - MODULE 3 (Ventes)
    // ============================================================
    
    Route::get('/export/factures/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->input('date_from'),
            'date_to'   => $request->input('date_to'),
            'status'    => $request->input('status'),
            'customer_id' => $request->input('customer_id'),
        ];
        return (new InvoicesPdfExport($filters))->generate();
    })->name('export.invoices.pdf');
    
    Route::get('/export/devis/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->input('date_from'),
            'date_to'   => $request->input('date_to'),
            'status'    => $request->input('status'),
            'customer_id' => $request->input('customer_id'),
        ];
        return (new \App\Exports\QuotesPdfExport($filters))->generate();
    })->name('export.quotes.pdf');

//   // ✅ NOUVELLE ROUTE : Export individuel d'une Proforma
// Route::get('/proforma-pdf/{id}', function ($id) {
//     try {
//         $quote = \App\Models\Module3\Quote::with(['customer', 'items.product'])->findOrFail($id);
        
//         if (!$quote) {
//             return redirect()->back()->with('error', 'Proforma introuvable.');
//         }
        
//         if ($quote->items->count() === 0) {
//             return redirect()->back()->with('error', 'Cette Proforma ne contient aucun article.');
//         }
        
//         // ✅ Utiliser le bon nom de classe
//         $export = new \App\Exports\QuotePdfExport();
//         return $export->generate($quote);
        
//     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
//         \Log::error('Proforma non trouvée: ' . $e->getMessage());
//         return redirect()->back()->with('error', 'Proforma introuvable.');
//     } catch (\Exception $e) {
//         \Log::error('Erreur export Proforma: ' . $e->getMessage());
//         \Log::error('Stack trace: ' . $e->getTraceAsString());
//         return redirect()->back()->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
//     }
// })->name('export.proforma.pdf');
    // ============================================================
    // EXPORTS PDF - MODULE 4 (SAV)
    // ============================================================
    
    Route::get('/sav/tickets/{id}/pdf', function ($id) {
        $ticket = \App\Models\Module5\SavTicket::findOrFail($id);
        return (new TicketPdfExport($ticket))->generate();
    })->name('module5.tickets.pdf');
    
    // ============================================================
    // EXPORTS PDF - MODULE ANALYTICS
    // ============================================================
    
    Route::get('/export/analytics/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->input('date_from'),
            'date_to'   => $request->input('date_to'),
            'category'  => $request->input('category'),
            'supplier'  => $request->input('supplier'),
        ];
        return (new AnalyticsPdfExport($filters))->generate();
    })->name('export.analytics.pdf');
    
});