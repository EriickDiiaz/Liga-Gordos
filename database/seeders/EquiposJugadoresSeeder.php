<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipo;
use App\Models\Jugador;
use Faker\Factory as Faker;

class EquiposJugadoresSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Crear 32 equipos
        for ($i = 1; $i <= 32; $i++) {
            $equipo = Equipo::create([
                'nombre' => $faker->unique()->company . ' FC',
                'logo' => 'img/default-team.png',
                'color_primario' => $faker->hexColor,
                'color_secundario' => $faker->hexColor,
                'estado' => $faker->boolean(80), // 80% de probabilidad de estar activo
            ]);

            // Crear 15 jugadores para cada equipo
            for ($j = 1; $j <= 15; $j++) {
                $tipo = $faker->randomElement(['habilidoso', 'brazalete', 'portero']);
                
                // Asegurarse de que haya al menos un portero por equipo
                if ($j == 1) {
                    $tipo = 'portero';
                }

                Jugador::create([
                    'nombre' => $faker->name,
                    'cedula' => $faker->unique()->numerify('##########'), // 10 dígitos
                    'fecha_nacimiento' => $faker->date('Y-m-d', '-18 years'), // Jugadores mayores de 18 años
                    'dorsal' => $j, // Números del 1 al 15
                    'tipo' => $tipo,
                    'foto' => 'img/default-player.png',
                    'equipo_id' => $equipo->id,
                ]);
            }
        }
    }
}

