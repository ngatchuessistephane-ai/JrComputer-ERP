use App\Models\Module1\Product;
use Illuminate\Support\Facades\Log;

protected function schedule(Schedule $schedule)
{
    // Vérification quotidienne des stocks bas
    $schedule->call(function () {
        $lowStockProducts = Product::whereRaw('quantity <= alert_threshold')->get();
        foreach ($lowStockProducts as $product) {
            // Ici on peut envoyer une notification (email, broadcast). Pour l'instant on log
            Log::channel('daily')->warning('Stock bas : ' . $product->name . ' (Réf: ' . $product->reference . ') quantité : ' . $product->quantity);
            // TODO: implémenter notification temps réel via Reverb
        }
    })->dailyAt('08:00');
}