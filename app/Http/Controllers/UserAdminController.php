<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserAdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->administrador = !$user->administrador;
        $user->save();

        return response()->json(['success' => true, 'administrador' => $user->administrador]);
    }

    public function toggleActivo($id)
    {
        $user = User::findOrFail($id);
        $user->activo = !$user->activo;
        $user->save();

        return response()->json(['success' => true, 'activo' => $user->activo]);
    }
}
