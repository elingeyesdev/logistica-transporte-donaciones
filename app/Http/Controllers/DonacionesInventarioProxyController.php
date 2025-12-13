<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DonacionesInventarioProxyController extends Controller
{
    /**
     * GET /api/gateway/donaciones/inventario/por-producto
     * Proxy a MS Donaciones: GET /api/inventario/por-producto
     */
    public function porProducto(Request $request)
    {
        $baseUrl = rtrim(env('MS_DONACIONES_URL', ''), '/');

        if (!$baseUrl) {
            return response()->json([
                'success' => false,
                'error'   => 'MS_DONACIONES_URL no está configurado',
            ], 500);
        }

        $targetUrl = $baseUrl . '/api/inventario/por-producto';
        $started   = microtime(true);

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->forwardedHeaders($request))
                ->get($targetUrl, $request->query());

            $this->storeLog('inventario_por_producto', $request, $response, $targetUrl, $started);

            return response($response->body(), $response->status())
                ->header('Content-Type', $response->header('Content-Type', 'application/json'));

        } catch (\Throwable $e) {
            $this->storeException('inventario_por_producto', $request, $e, $targetUrl, $started);

            return response()->json([
                'success' => false,
                'error'   => 'Error al llamar a Donaciones',
            ], 502);
        }
    }

    protected function forwardedHeaders(Request $request): array
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        if ($request->hasHeader('X-Client-System')) {
            $headers['X-Client-System'] = $request->header('X-Client-System');
        }

        if ($request->hasHeader('Authorization')) {
            $headers['Authorization'] = $request->header('Authorization');
        }

        return $headers;
    }

    protected function storeLog(
        string  $operation,
        Request $request,
        $response,
        string  $targetUrl,
        float   $startedAt
    ): void {
        $durationMs = (int) ((microtime(true) - $startedAt) * 1000);

        $entry = [
            'ts'          => now()->toIso8601String(),
            'operation'   => $operation,
            'method'      => $request->method(),
            'status'      => $response->status(),
            'target_url'  => $targetUrl,
            'caller'      => $request->header('X-Client-System'),
            'ip'          => $request->ip(),
            'duration_ms' => $durationMs,
        ];

        $cacheKey = "gateway:donaciones:inventario:{$operation}";
        $logs     = Cache::get($cacheKey, []);

        $logs[] = $entry;
        Cache::put($cacheKey, $logs, now()->addDay());

        Log::info('Gateway Donaciones inventario proxy', $entry);
    }

    protected function storeException(
        string    $operation,
        Request   $request,
        \Throwable $e,
        string    $targetUrl,
        float     $startedAt
    ): void {
        $durationMs = (int) ((microtime(true) - $startedAt) * 1000);

        $entry = [
            'ts'          => now()->toIso8601String(),
            'operation'   => $operation,
            'method'      => $request->method(),
            'status'      => 'exception',
            'error'       => $e->getMessage(),
            'target_url'  => $targetUrl,
            'caller'      => $request->header('X-Client-System'),
            'ip'          => $request->ip(),
            'duration_ms' => $durationMs,
        ];

        $cacheKey = "gateway:donaciones:inventario:{$operation}";
        $logs     = Cache::get($cacheKey, []);
        $logs[]   = $entry;

        Cache::put($cacheKey, $logs, now()->addDay());

        Log::error('Gateway Donaciones inventario proxy error', $entry);
    }
}
