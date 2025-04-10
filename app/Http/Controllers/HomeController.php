<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partido;
use App\Models\Equipo;
use App\Models\Noticia;
use App\Models\Torneo;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['welcome']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the welcome page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome()
    {
        $proximosPartidos = Partido::with(['equipoLocal', 'equipoVisitante', 'torneo'])
            ->where('fecha', '>=', now())
            ->where('estado', 'programado')
            ->orderBy('fecha', 'asc')
            ->take(3)
            ->get();
           
        $ultimasNoticias = Noticia::orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        $equipos = Equipo::where('estado', 1)->get();
        $torneosActivos = Torneo::where('estado', 'en_curso')->count();
        $patrocinadores = \App\Models\Patrocinador::all();
        
        return view('welcome', compact('proximosPartidos', 'equipos', 'torneosActivos', 'patrocinadores', 'ultimasNoticias'));
    }
}
