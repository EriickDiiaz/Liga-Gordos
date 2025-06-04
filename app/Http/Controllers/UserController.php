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
        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no debe exceder 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.max' => 'El correo electrónico no debe exceder 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'roles.array' => 'Los roles deben ser un arreglo.',
            'active.in' => 'El estado debe ser activo o inactivo.',
            'active_until.date' => 'La fecha de activación debe ser una fecha válida.',
            'active_until.after_or_equal' => 'La fecha de activación debe ser hoy o una fecha futura.',
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'active' => 'nullable|in:0,1,true,false,on,off',
            'active_until' => 'nullable|date|after_or_equal:today',
        ], $messages);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'active' => $request->boolean('active', false),
                'active_until' => $request->active_until,
            ]);

            if ($request->has('roles') && !empty($request->roles)) {
                // Convertir IDs de roles a nombres de roles
                $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
                $user->syncRoles($roleNames);
            }

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario creado exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function edit(User $usuario)
    {
        $roles = Role::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no debe exceder 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.max' => 'El correo electrónico no debe exceder 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'roles.array' => 'Los roles deben ser un arreglo.',
            'active.in' => 'El estado debe ser activo o inactivo.',
            'active_until.date' => 'La fecha de activación debe ser una fecha válida.',
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'active' => 'nullable|in:0,1,true,false,on,off',
            'active_until' => 'nullable|date',
        ], $messages);

        try {
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->active = $request->boolean('active', false);
            $usuario->active_until = $request->active_until;

            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->password);
            }

            $usuario->save();

            if ($request->has('roles') && !empty($request->roles)) {
                // Convertir IDs de roles a nombres de roles
                $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
                $usuario->syncRoles($roleNames);
            } else {
                $usuario->syncRoles([]);
            }

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
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