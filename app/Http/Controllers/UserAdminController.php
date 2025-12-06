<?php

namespace App\Http\Controllers;
use App\Models\Conductor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Rol;
use App\Models\TipoLicencia;
class UserAdminController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $roles = Rol::orderBy('titulo_rol')->get();
        $licencias = TipoLicencia::orderBy('id_licencia')->get();
         if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success'   => true,
                'users'     => $users,
                'roles'     => $roles,
                'licencias' => $licencias,
            ]);
        }
        return view('users.index', compact('users', 'roles', 'licencias'));
    }

    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);

        $user->administrador = !$user->administrador;
        $user->save();
        if ($user->administrador) {
            if (!$user->hasRole('admin')) {
                $user->assignRole('admin');
            }
        } else {
            if ($user->hasRole('admin')) {
                $user->removeRole('admin');
            }
        }

        return response()->json([
            'success'       => true,
            'administrador' => $user->administrador,
        ]);
    }


    public function toggleActivo($id)
    {
        $user = User::findOrFail($id);
        $user->activo = !$user->activo;
        $user->save();

        return response()->json(['success' => true, 'activo' => $user->activo]);
    }

   public function cambiarRol($id, Request $request)
    {
    $user = User::findOrFail($id);
    $createdConductor = false;  
    $validated = $request->validate([
        'id_rol' => ['required', 'integer', 'exists:rol,id_rol'],
    ]);

    $rol = Rol::findOrFail($validated['id_rol']);

    if (stripos($rol->titulo_rol, 'admin') !== false) {
        return response()->json([
            'success' => false,
            'message' => 'No se puede asignar el rol de administrador desde esta pantalla.',
        ], 403);
    }

    $user->id_rol = $rol->id_rol;
    $user->save();

    if (stripos($rol->titulo_rol, 'conductor') !== false) {

        $fecha = $request->input('fecha_nacimiento');
        $lic   = $request->input('id_licencia');

        if (!$fecha || !$lic) {
            return response()->json([
                'success' => false,
                'message' => 'Debe indicar la fecha de nacimiento y el tipo de licencia del conductor.',
            ], 422);
        }

        Conductor::updateOrCreate(
            ['ci' => $user->ci],
            [
                'nombre'           => $user->nombre,
                'apellido'         => $user->apellido,
                'fecha_nacimiento' => $fecha,
                'ci'               => $user->ci,
                'celular'          => $user->telefono,
                'id_licencia'      => $lic,
            ]
        );
        $createdConductor = true;

    }

    return response()->json([
            'success'          => true,
            'conductorCreated' => $createdConductor,
        ]);}
}
