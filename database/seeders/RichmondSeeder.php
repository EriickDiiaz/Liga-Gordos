<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\Jugador;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RichmondSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $TeamRichmond = Equipo::create([
            'id' => '9999',
            'nombre' => 'AFC Richmond',
            'logo' => 'img/afc-richmond.png',
            'color_primario' => '#1D2568',
            'color_secundario' => '#90042E',
            'estado' => '1',
        ]);



        $PlayersRichmond = [
            ['Tom OBrien', '00000001', '1995/03/01', '1', 'Portero', 'afc-richmond/tom-obrien.png', '9999'],
            ['Arlo Dixon', '00000002', '1996/06/04', '2', 'Brazalete', 'afc-richmond/arlo-dixon.png', '9999'],
            ['Tommy Winchester', '00000003', '1996/06/19', '4', 'Brazalete', 'afc-richmond/tommy-winchester.png', '9999'],
            ['Isaac McAdoo', '00000004', '1996/06/04', '5', 'Habilidoso', 'afc-richmond/isaac-mcadoo.png', '9999'],
            ['Roy Kent', '00000005', '1982/02/15', '6', 'Habilidoso', 'afc-richmond/roy-kent.png', '9999'],
            ['Richard Montlaur', '00000006', '1994/07/14', '8', 'Habilidoso', 'afc-richmond/richard-montlaur.png', '9999'],
            ['Jamie Tart', '00000007', '1997/10/20', '9', 'Habilidoso', 'afc-richmond/jamie-tartt.png', '9999'],
            ['Zava', '00000008', '1980/01/01', '10', 'Habilidoso', 'afc-richmond/zava.png', '9999'],
            ['Colin Hughes', '00000009', '1995/08/12', '12', 'Habilidoso', 'afc-richmond/colin-hughes.png', '9999'],
            ['Jan Maas', '00000010', '1993/09/01', '13', 'Brazalete', 'afc-richmond/jan-maas.png', '9999'],
            ['Dani Rojas', '00000011', '1995/09/16', '14', 'Habilidoso', 'afc-richmond/dani-rojas.png', '9999'],
            ['Jeff Goodman', '00000012', '1992/07/08', '17', 'Brazalete', 'afc-richmond/jeff-goodman.png', '9999'],
            ['Declan Cockburn', '00000013', '1994/09/14', '19', 'Brazalete', 'afc-richmond/declan-cockburn.png', '9999'],
            ['Pablo Reynolds', '00000014', '1991/06/01', '20', 'Brazalete', 'afc-richmond/pablo-reynolds.png', '9999'],
            ['Moe Bumbercatch', '00000015', '1996/08/14', '21', 'Habilidoso', 'afc-richmond/moe-bumbercatch.png', '9999'],
            ['Sam Obisanya', '00000016', '2000/01/11', '24', 'Habilidoso', 'afc-richmond/sam-obisanya.png', '9999'],
            ['Thierry Zoreaux', '00000017', '1993/10/11', '81', 'Portero', 'afc-richmond/thierry-zorreaux.png', '9999'],
        ];

        foreach ($PlayersRichmond as $PlayerRichmond){
            Jugador::create([
                'nombre' => $PlayerRichmond[0],
                'cedula' => $PlayerRichmond[1],
                'fecha_nacimiento' => $PlayerRichmond[2],
                'dorsal' => $PlayerRichmond[3],
                'tipo' => $PlayerRichmond[4],
                'foto' => $PlayerRichmond[5],
                'equipo_id' => $PlayerRichmond[6],
            ]);
        } 
    }
}
