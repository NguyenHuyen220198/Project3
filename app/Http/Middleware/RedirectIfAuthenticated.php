<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            // Chỗ này ->route(tên của route)
            return redirect()->route('admin.index'); // Sai ở đây, bởi vì cái route / nó không tồn tại ở file routes.php, bây giờ mình sẽ phải sửa thành cái route bảng điểu khiển
        }

        return $next($request);
    }
}

