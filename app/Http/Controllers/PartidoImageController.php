<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\Patrocinador;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PartidoImageController extends Controller
{
    public function generarImagen(Partido $partido)
    {
        try {
            Log::info('Generando imagen para partido: ' . $partido->id);
            
            // Crear el manager de imágenes
            $manager = new ImageManager(new Driver());
            
            // Dimensiones de la imagen
            $width = 1200;
            $height = 630;
            
            // Crear imagen base
            $image = $manager->create($width, $height);
            
            // Agregar fondo
            $this->addBackground($image, $width, $height);
            
            // Agregar información del partido
            $this->addMatchInfo($image, $partido, $width, $height);
            
            // Agregar logos de equipos (si existen)
            $this->addTeamLogos($image, $partido, $width, $height);
            
            // Agregar patrocinadores
            $this->addSponsors($image, $width, $height);
            
            // Agregar branding
            $this->addBranding($image, $width, $height);
            
            Log::info('Imagen generada exitosamente para partido: ' . $partido->id);
            
            // Retornar la imagen como respuesta para descarga
            return response($image->toPng(), 200)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="partido_' . $partido->id . '.png"');
                
        } catch (\Exception $e) {
            Log::error('Error generando imagen: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Retornar una imagen de error simple
            return $this->generateErrorImage();
        }
    }
    
    public function mostrarImagen(Partido $partido)
    {
        try {
            Log::info('Mostrando imagen para partido: ' . $partido->id);
            
            $manager = new ImageManager(new Driver());
            
            $width = 1200;
            $height = 630;
            
            $image = $manager->create($width, $height);
            
            $this->addBackground($image, $width, $height);
            $this->addMatchInfo($image, $partido, $width, $height);
            $this->addTeamLogos($image, $partido, $width, $height);
            $this->addSponsors($image, $width, $height);
            $this->addBranding($image, $width, $height);
            
            return response($image->toPng(), 200)
                ->header('Content-Type', 'image/png')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            Log::error('Error mostrando imagen: ' . $e->getMessage());
            return $this->generateErrorImage();
        }
    }
    
    private function generateErrorImage()
    {
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->create(800, 400);
            $image->fill('#ff6b6b');
            
            // Agregar texto de error
            $image->text('Error al generar imagen', 400, 150, function ($font) {
                $font->size(32);
                $font->color('#ffffff');
                $font->align('center');
            });
            
            $image->text('Por favor, intenta nuevamente', 400, 200, function ($font) {
                $font->size(20);
                $font->color('#ffffff');
                $font->align('center');
            });
            
            return response($image->toPng(), 200)
                ->header('Content-Type', 'image/png');
                
        } catch (\Exception $e) {
            // Si incluso la imagen de error falla, retornar un 500
            return response('Error interno del servidor', 500);
        }
    }
    
    private function addBackground($image, $width, $height)
    {
        // Fondo simple sin gradiente para evitar errores
        $image->fill('#1a1a2e');
        
        // Agregar algunos elementos decorativos simples
        try {
            // Líneas decorativas
            for ($i = 0; $i < 5; $i++) {
                $y = 100 + ($i * 100);
                $image->drawLine(0, $y, $width, $y, function ($draw) {
                    $draw->color('#2c2c54');
                    $draw->width(1);
                });
            }
        } catch (\Exception $e) {
            Log::warning('Error agregando decoración de fondo: ' . $e->getMessage());
        }
    }
    
    private function addTeamLogos($image, $partido, $width, $height)
    {
        try {
            // Logo equipo local
            $logoLocalPath = public_path($partido->equipoLocal->logo);
            if (file_exists($logoLocalPath) && is_readable($logoLocalPath)) {
                $logoLocal = $this->resizeImage($logoLocalPath, 120, 120);
                if ($logoLocal) {
                    $image->place($logoLocal, 'top-left', 100, 100);
                }
            } else {
                // Crear círculo con iniciales
                $this->addTeamInitials($image, $partido->equipoLocal->nombre, 160, 160, '#3498db');
            }
            
            // Logo equipo visitante
            $logoVisitantePath = public_path($partido->equipoVisitante->logo);
            if (file_exists($logoVisitantePath) && is_readable($logoVisitantePath)) {
                $logoVisitante = $this->resizeImage($logoVisitantePath, 120, 120);
                if ($logoVisitante) {
                    $image->place($logoVisitante, 'top-right', 100, 100);
                }
            } else {
                // Crear círculo con iniciales
                $this->addTeamInitials($image, $partido->equipoVisitante->nombre, $width - 160, 160, '#e74c3c');
            }
        } catch (\Exception $e) {
            Log::warning('Error agregando logos: ' . $e->getMessage());
        }
    }
    
    private function addTeamInitials($image, $teamName, $x, $y, $color)
    {
        try {
            // Obtener iniciales
            $words = explode(' ', $teamName);
            $initials = '';
            foreach ($words as $word) {
                if (strlen($word) > 0) {
                    $initials .= strtoupper($word[0]);
                    if (strlen($initials) >= 2) break;
                }
            }
            
            if (empty($initials)) {
                $initials = strtoupper(substr($teamName, 0, 2));
            }
            
            // Dibujar círculo simple
            $image->drawCircle($x, $y, function ($draw) use ($color) {
                $draw->radius(60);
                $draw->background($color);
            });
            
            // Agregar texto
            $image->text($initials, $x, $y, function ($font) {
                $font->size(30);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('middle');
            });
        } catch (\Exception $e) {
            Log::warning('Error agregando iniciales: ' . $e->getMessage());
        }
    }
    
    private function addMatchInfo($image, $partido, $width, $height)
    {
        try {
            // Nombres de equipos
            $image->text($partido->equipoLocal->nombre, 100, 300, function ($font) {
                $font->size(24);
                $font->color('#ffffff');
                $font->align('left');
            });
            
            $image->text($partido->equipoVisitante->nombre, $width - 100, 300, function ($font) {
                $font->size(24);
                $font->color('#ffffff');
                $font->align('right');
            });
            
            // Resultado o VS
            $resultado = $this->getResultadoTexto($partido);
            $image->text($resultado, $width / 2, 200, function ($font) {
                $font->size(40);
                $font->color('#00ff88');
                $font->align('center');
                $font->valign('middle');
            });
            
            // Información del torneo
            $torneoInfo = '';
            if ($partido->torneo) {
                $torneoInfo = $partido->torneo->nombre;
                if ($partido->fase) {
                    $torneoInfo .= ' - ' . $partido->fase;
                }
            } elseif ($partido->esAmistoso()) {
                $torneoInfo = 'Partido Amistoso';
            }
            
            if ($torneoInfo) {
                $image->text($torneoInfo, $width / 2, 350, function ($font) {
                    $font->size(20);
                    $font->color('#cccccc');
                    $font->align('center');
                });
            }
            
            // Fecha del partido
            $fecha = $partido->fecha->format('d/m/Y - H:i');
            $image->text($fecha, $width / 2, 380, function ($font) {
                $font->size(18);
                $font->color('#cccccc');
                $font->align('center');
            });
            
            // Estado del partido
            $estado = ucfirst($partido->estado);
            $colorEstado = $this->getColorEstado($partido->estado);
            
            $image->text($estado, $width / 2, 410, function ($font) use ($colorEstado) {
                $font->size(16);
                $font->color($colorEstado);
                $font->align('center');
            });
            
            // Información adicional según el estado
            if ($partido->fecha->isFuture()) {
                $tiempoRestante = $partido->fecha->diffForHumans();
                $image->text($tiempoRestante, $width / 2, 440, function ($font) {
                    $font->size(14);
                    $font->color('#ffd700');
                    $font->align('center');
                });
            } elseif ($partido->fecha->isPast() && $partido->estado == 'finalizado') {
                $tiempoPasado = $partido->fecha->diffForHumans();
                $image->text($tiempoPasado, $width / 2, 440, function ($font) {
                    $font->size(14);
                    $font->color('#888888');
                    $font->align('center');
                });
            }
            
        } catch (\Exception $e) {
            Log::warning('Error agregando información del partido: ' . $e->getMessage());
        }
    }
    
    private function addSponsors($image, $width, $height)
    {
        try {
            $patrocinadores = Patrocinador::where('activo', true)->take(3)->get();
            
            if ($patrocinadores->count() > 0) {
                $image->text('Patrocinado por:', $width / 2, $height - 100, function ($font) {
                    $font->size(14);
                    $font->color('#888888');
                    $font->align('center');
                });
                
                $startX = ($width - (count($patrocinadores) * 150)) / 2;
                
                foreach ($patrocinadores as $index => $patrocinador) {
                    $x = $startX + ($index * 150);
                    $image->text($patrocinador->nombre, $x, $height - 70, function ($font) {
                        $font->size(12);
                        $font->color('#888888');
                        $font->align('left');
                    });
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error agregando patrocinadores: ' . $e->getMessage());
        }
    }
    
    private function addBranding($image, $width, $height)
    {
        try {
            $image->text('Liga Gordos', 50, $height - 30, function ($font) {
                $font->size(12);
                $font->color('#666666');
                $font->align('left');
            });
            
            $fechaGeneracion = Carbon::now()->format('d/m/Y H:i');
            $image->text('Generado: ' . $fechaGeneracion, $width - 50, $height - 30, function ($font) {
                $font->size(10);
                $font->color('#666666');
                $font->align('right');
            });
        } catch (\Exception $e) {
            Log::warning('Error agregando branding: ' . $e->getMessage());
        }
    }
    
    private function resizeImage($path, $width, $height)
    {
        try {
            $manager = new ImageManager(new Driver());
            return $manager->read($path)->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } catch (\Exception $e) {
            Log::warning('Error redimensionando imagen: ' . $e->getMessage());
            return null;
        }
    }
    
    private function getResultadoTexto($partido)
    {
        if ($partido->goles_local !== null && $partido->goles_visitante !== null) {
            return $partido->goles_local . ' - ' . $partido->goles_visitante;
        }
        return 'VS';
    }
    
    private function getColorEstado($estado)
    {
        switch ($estado) {
            case 'programado':
                return '#3498db';
            case 'en_curso':
                return '#27ae60';
            case 'finalizado':
                return '#95a5a6';
            default:
                return '#cccccc';
        }
    }
}
