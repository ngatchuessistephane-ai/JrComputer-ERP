use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;

public function boot()
{
    // ...
    app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
}