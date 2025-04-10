<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    public function index()
    {
        $noticias = Noticia::all();
        return view('noticias.index', compact('noticias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('noticias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validateNoticia($request);

        $noticia = new Noticia($request->all());

        $noticia->save();

        return redirect()->route('noticias.index')->with('success', 'Noticia creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Noticia $noticia)
    {
        return view('noticias.show', compact('noticia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Noticia $noticia)
    {
        return view('noticias.edit', compact('noticia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Noticia $noticia)
{
    $this->validateNoticia($request, $noticia->id);

    // Asignar los datos actualizados al modelo
    $noticia->fill($request->all());

    // Guardar los cambios en la base de datos
    $noticia->save();

    return redirect()->route('noticias.index')->with('success', 'Noticia actualizada exitosamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Noticia $noticia)
    {
        $noticia->delete();
        
        return redirect()->route('noticias.index')->with('success', 'Noticia eliminada exitosamente.');
    }

    private function validateNoticia(Request $request, $id = null)
    {
        $rules = [
            'titulo' => 'required',
            'contenido' => 'required',
        ];

        $messages = [
            'titulo.required' => 'La noticia debe tener un titulo.',
            'contenido.required' => 'Esta noticia debe tener un contenido.',
        ];

        return $request->validate($rules, $messages);
    }
}
