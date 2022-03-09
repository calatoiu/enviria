<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Furnizor;

class FurnizorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     *
     */
    public function run()
    {
        $furnizori = [
            [
                'id' => 'PFA',
                'CUI' => '28630120',
                'Denumire' => 'CALATOIU ZOITA P.F.A.',
                'ContBancar' => 'RO75INGB0000999903587591',
                'Banca' => 'ING Bank',
                'Sediu' => 'Constanța, str. Pescarilor, nr. 39',
                'Judet' => 'Constanța',
                'NrRegCom' => 'F13/1031/2011',
            ],

            [
                'id' => 'MEX',
                'CUI' => '35669009',
                'Denumire' => 'MEDIU EXPRES PLUS SRL',
                'ContBancar' => 'RO20INGB0000999905689795',
                'Banca' => 'ING Bank',
                'Sediu' => 'Constanța, str. Pescarilor, nr. 39',
                'Judet' => 'Constanța',
                'NrRegCom' => 'J13/389/2016',
            ],
            [
                'id' => 'KEE',
                'CUI' => '43096611',
                'Denumire' => 'KEEP ENVIRO SRL',
                'ContBancar' => 'RO61INGB0000999910722096',
                'Banca' => 'ING Bank',
                'Sediu' => 'Constanța, str. Pescarilor, nr. 39',
                'Judet' => 'Constanța',
                'NrRegCom' => 'J13/2457/2020',
            ],
         ];

         foreach ($furnizori as $furnizor) {
             Furnizor::create($furnizor);
         }
    }
}
