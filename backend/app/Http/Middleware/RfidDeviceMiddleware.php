<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RfidDeviceMiddleware
{
    /**
     * Autentikasi perangkat ESP32 via API key.
     * ESP32 wajib kirim header X-Device-Key atau body param device_key.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-Device-Key') ?? $request->input('device_key');

        if (!$key || $key !== config('rfid.device_key')) {
            return response()->json([
                'error'   => 'Unauthorized',
                'message' => 'API key perangkat tidak valid. Pastikan X-Device-Key header sudah benar.',
            ], 401);
        }

        return $next($request);
    }
}
