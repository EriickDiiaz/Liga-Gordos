<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:Gestionar Usuarios']);
    }

    public function index()
    {
        $users = User::with('roles')->get();
        return view('usuarios.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $equipos = Equipo::all();
        return view('usuarios.create', compact('roles', 'equipos'));
    }

    public function store(Request $request)
    {
        // Validar datos
        $validatedData = $this->validateUsuario($request);
        
        // Verificar si se seleccionó el rol de Capitán
        $roleId = $request->input('roles');
        $role = Role::findById($roleId);
        $isCapitan = $role->name === 'Capitán';

        // Si es Capitán, el equipo_id es obligatorio
        if ($isCapitan && empty($validatedData['equipo_id'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['equipo_id' => 'El campo equipo es obligatorio para usuarios con rol de Capitán.']);
        }

        // Crear usuario
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'equipo_id' => $isCapitan ? $validatedData['equipo_id'] : null,
        ]);

        // Asignar rol
        $user->assignRole($role);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $usuario)
    {
        $roles = Role::all();
        $equipos = Equipo::all();
        return view('usuarios.edit', compact('usuario', 'roles', 'equipos'));
    }

    public function update(Request $request, User $usuario)
    {
        // Validar datos
        $validatedData = $this->validateUsuario($request, $usuario->id);
        
        // Verificar si se seleccionó el rol de Capitán
        $roleId = $request->input('roles');
        $role = Role::findById($roleId);
        $isCapitan = $role->name === 'Capitán';

        // Si es Capitán, el equipo_id es obligatorio
        if ($isCapitan && empty($validatedData['equipo_id'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['equipo_id' => 'El campo equipo es obligatorio para usuarios con rol de Capitán.']);
        }

        // Actualizar datos básicos
        $updateData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'equipo_id' => $isCapitan ? $validatedData['equipo_id'] : null,
        ];

        // Actualizar contraseña si se proporcionó
        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        $usuario->update($updateData);

        // Sincronizar roles
        $usuario->syncRoles([$role->name]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    protected function validateUsuario(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'max:255', $id ? "unique:users,email,{$id}" : 'unique:users'],
            'password' => $id ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'roles' => 'required|exists:roles,id',
            'equipo_id' => 'nullable|exists:equipos,id',
        ];

        $messages = [
            'name.required' => 'El nombre del usuario es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'roles.required' => 'Debe seleccionar al menos un rol.',
            'roles.exists' => 'El rol seleccionado no existe.',
            'equipo_id.exists' => 'El equipo seleccionado no existe.',
        ];

        return $request->validate($rules, $messages);
    }
}

