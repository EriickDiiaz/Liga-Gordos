<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::with('roles')->get();
        return view('usuarios.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'active' => 'boolean',
            'active_until' => 'nullable|date|after_or_equal:today',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active' => $request->has('active'),
            'active_until' => $request->active_until,
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $usuario)
    {
        $roles = Role::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'active' => 'boolean',
            'active_until' => 'nullable|date',
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->active = $request->has('active');
        $usuario->active_until = $request->active_until;
        
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        if ($request->has('roles')) {
            $usuario->syncRoles($request->roles);
        } else {
            $usuario->syncRoles([]);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $usuario)
    {
        // Evitar eliminar al usuario actual
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Toggle user active status.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function toggleActive(User $usuario)
    {
        // Evitar desactivar al usuario actual
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $usuario->active = !$usuario->active;
        $usuario->save();

        $status = $usuario->active ? 'activado' : 'desactivado';
        return redirect()->route('usuarios.index')
            ->with('success', "Usuario {$status} exitosamente.");
    }

    /**
     * Set user active until date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function setActiveUntil(Request $request, User $usuario)
    {
        $request->validate([
            'active_until' => 'required|date|after_or_equal:today',
        ]);

        $usuario->active = true;
        $usuario->active_until = $request->active_until;
        $usuario->save();

        return redirect()->route('usuarios.index')
            ->with('success', "Usuario activado hasta {$usuario->active_until->format('d/m/Y')}.");
    }
}

