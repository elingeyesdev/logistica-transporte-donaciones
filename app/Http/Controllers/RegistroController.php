<?php
namespace App\Http\Controllers;

use App\Services\Traceability\MicroserviceClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RegistroController extends Controller
{
   public function getSimplePersonaByCi(Request $request, string $ci)
{
    $ci       = trim($ci);
    $cacheKey = "gateway:registro_simple:ci:$ci";

    if ($cached = Cache::get($cacheKey)) {
        return response()->json($cached);
    }

    $client = new MicroserviceClient($ci);
    $result = $client->fetchFirstSimpleIdentityByCi($ci);

    $persona = $result['persona'] ?? [
        'nombre'   => null,
        'apellido' => null,
        'telefono' => null,
    ];

    $payload = [
        'success' => $result['success'],
        'ci'      => $ci,
        'found'   => $result['found'],
        'system'  => $result['system'],

        'data'    => [
            'nombre'   => $persona['nombre']   ?? null,
            'apellido' => $persona['apellido'] ?? null,
            'telefono' => $persona['telefono'] ?? null,
        ],

        'nombre'   => $persona['nombre']   ?? null,
        'apellido' => $persona['apellido'] ?? null,
        'telefono' => $persona['telefono'] ?? null,

        'trace' => [
            'requested_at' => now()->toIso8601String(),
            'caller'       => $request->header('X-Client-System'),
            'attempts'     => $result['attempts'],
        ],
    ];

    Cache::put($cacheKey, $payload, now()->addMinutes(5));

    return response()->json($payload);
}

}
