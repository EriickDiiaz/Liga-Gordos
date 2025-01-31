<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index()
    {
        $equipos = Equipo::all();
        return view('equipos.index', compact('equipos'));
    }

    public function create()
    {
        if (request()->ajax()) {
            return view('equipos._form')->render();
        }
        return view('equipos.create');
    }

    public function store(Request $request)
    {
        $this->validateEquipo($request);

        $equipo = new Equipo($request->all());

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('logos'), $logoName);
            $equipo->logo = 'logos/' . $logoName;
        } else {
            $equipo->logo = 'img/default-team.png';
        }

        $equipo->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Equipo creado exitosamente.']);
        }

        return redirect()->route('equipos.index')->with('success', 'Equipo creado exitosamente.');
    }

    public function show(Equipo $equipo)
    {
        $equipo->load('jugadores');
        return view('equipos.show', compact('equipo'));
    }

    public function edit(Equipo $equipo)
    {
        if (request()->ajax()) {
            return view('equipos._form', compact('equipo'))->render();
        }
        return view('equipos.edit', compact('equipo'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $this->validateEquipo($request, $equipo->id);

        $equipo->fill($request->except('logo'));

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('logos'), $logoName);
            $equipo->logo = 'logos/' . $logoName;
        }

        $equipo->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Equipo actualizado exitosamente.']);
        }

        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado exitosamente.');
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return redirect()->route('equipos.index')->with('success', 'Equipo eliminado exitosamente.');
    }

    private function validateEquipo(Request $request, $id = null)
    {
        $rules = [
            'nombre' => 'required|unique:equipos,nombre' . ($id ? ",$id" : ''),
            'color_primario' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'boolean',
        ];

        $messages = [
            'nombre.required' => 'El nombre del equipo es obligatorio.',
            'nombre.unique' => 'Este nombre de equipo ya está en uso.',
            'color_primario.required' => 'El color primario es obligatorio.',
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.mimes' => 'El logo debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'logo.max' => 'El logo no debe ser mayor a 2MB.',
            'estado.boolean' => 'El estado debe ser activo o inactivo.',
        ];

        return $request->validate($rules, $messages);
    }
}

