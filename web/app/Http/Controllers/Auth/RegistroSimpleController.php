<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegistroSimpleController extends Controller
{
    /**
     * GET /api/registro/ci/{ci}
     * Devuelve datos básicos de un usuario por CI para autocompletar registros
     * usados por el API Gateway.
     */
    public function showByCi(Request $request, string $ci)
    {
        $clientSystem = $request->header('X-Client-System', 'unknown');

        Log::info('RegistroSimple lookup recibido', [
            'ci'            => $ci,
            'client_system' => $clientSystem,
            'ip'            => $request->ip(),
        ]);

        $user = User::where('ci', $ci)->first();

        // IMPORTANTE: no devolver 404 si no hay usuario.
        if (!$user) {
            return response()->json([
                'success' => true,
                'system'  => 'logistica',
                'ci'      => $ci,
                'found'   => false,
                'data'    => null,
            ], 200);
        }

        return response()->json([
            'success' => true,
            'system'  => 'logistica',
            'ci'      => $ci,
            'found'   => true,
            'data'    => [
                'ci'                 => $user->ci,
                'nombre'             => $user->nombre,
                'apellido'           => $user->apellido,
                'telefono'           => $user->telefono,
                'correo_electronico' => $user->correo_electronico,
            ],
        ], 200);
    }
}
