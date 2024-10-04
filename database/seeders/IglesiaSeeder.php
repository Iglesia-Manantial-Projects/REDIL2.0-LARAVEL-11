<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Iglesia;

class IglesiaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Iglesia::create([
      'nombre' => 'Iglesia el redil',
      'logo' => 'logo.png',
      'municipio_id' => 1089,
      'pais_id' => 45,
      'latitud' => '4.0747',
      'longitud' => '-76.2016'
    ]);
  }
}
