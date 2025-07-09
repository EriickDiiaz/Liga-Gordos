<?php

namespace App\Http\Controllers;

use App\Models\Patrocinador;
use Illuminate\Http\Request;

class PatrocinadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patrocinadores = Patrocinador::all();
        return view('patrocinador.index', compact('patrocinadores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patrocinador.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validatePatrocinador($request);

        $patrocinador = new Patrocinador($request->all());

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('patrocinadores'), $logoName);
            $patrocinador->logo = 'patrocinadores/' . $logoName;
        } else {
            $patrocinador->logo = 'img/default-team.png';
        }

        $patrocinador->save();

        return redirect()->route('patrocinador.index')->with('success', 'Patrocinador creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patrocinador $patrocinador)
    {
        return view('patrocinador.show', compact('patrocinador'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patrocinador $patrocinador)
    {
        return view('patrocinador.edit', compact('patrocinador'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patrocinador $patrocinador)
    {
        $this->validatePatrocinador($request, $patrocinador->id);
        
        $patrocinador->fill($request->except('logo'));
        
        if ($request->hasFile('logo')) {
            // Si hay un logo anterior y no es el logo por defecto, eliminarlo
            if ($patrocinador->logo && $patrocinador->logo != 'img/default-team.png') {
                if (file_exists(public_path($patrocinador->logo))) {
                    unlink(public_path($patrocinador->logo));
                }
            }
            
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('patrocinadores'), $logoName);
            $patrocinador->logo = 'patrocinadores/' . $logoName;
        }
        
        $patrocinador->save();
        
        return redirect()->route('patrocinador.index')->with('success', 'Patrocinador actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patrocinador $patrocinador)
    {
        // Si hay un logo y no es el logo por defecto, eliminarlo
        if ($patrocinador->logo && $patrocinador->logo != 'img/default-team.png') {
            if (file_exists(public_path($patrocinador->logo))) {
                unlink(public_path($patrocinador->logo));
            }
        }
        
        $patrocinador->delete();
        
        return redirect()->route('patrocinador.index')->with('success', 'Patrocinador eliminado exitosamente.');
    }

    private function validatePatrocinador(Request $request, $id = null)
    {
        $rules = [
            'nombre' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'instagram' => 'nullable',
            'tiktok' => 'nullable',
            'facebook' => 'nullable',
            'telefono' => 'nullable',
        ];

        $messages = [
            'nombre.required' => 'El nombre del equipo es obligatorio.',
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.mimes' => 'El logo debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'logo.max' => 'El logo no debe ser mayor a 2MB.',
        ];

        return $request->validate($rules, $messages);
    }
}
