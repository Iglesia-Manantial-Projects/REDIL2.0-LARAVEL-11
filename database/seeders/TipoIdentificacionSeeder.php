<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TipoIdentificacion;

class TipoIdentificacionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $tiposDeIdentificaciones = '[
        {"id":"1","nombre":"Registro civil","formularioDonacion":"si"},
        {"id":"2","nombre":"Tarjeta de identificación","formularioDonacion":"si"},
        {"id":"3","nombre":"Cédula de ciudadanía","formularioDonacion":"si"},
        {"id":"4","nombre":"Cédula extranjería","formularioDonacion":"si"},
        {"id":"5","nombre":"Licencia de manejo","formularioDonacion":"no"},
        {"id":"6","nombre":"Pasaporte","formularioDonacion":"si"},
        {"id":"7","nombre":"Matrícula consular","formularioDonacion":"no"},
        {"id":"8","nombre":"NIE","formularioDonacion":"no"},
        {"id":"9","nombre":"DNI","formularioDonacion":"si"},
        {"id":"10","nombre":"Pasaporte","formularioDonacion":"no"},
        {"id":"11","nombre":"CURP","formularioDonacion":"no"},
        {"id":"12","nombre":"DPI","formularioDonacion":"no"},
        {"id":"13","nombre":"INE","formularioDonacion":"no"},
        {"id":"14","nombre":"RUT","formularioDonacion":"no"},
        {"id":"15","nombre":"FOLIO","formularioDonacion":"no"},
        {"id":"16","nombre":"Cédula de identidad","formularioDonacion":"no"},
        {"id":"17","nombre":"Nit Empresa","formularioDonacion":"si"},
        {"id":"18","nombre":"Documento de identificación extranjero","formularioDonacion":"si"},
        {"id":"19","nombre":"DNI CUIT","formularioDonacion":"no"},
        {"id":"20","nombre":"Permiso especial de permanencia","formularioDonacion":"no"},
        {"id":"21","nombre":"DUI","formularioDonacion":"no"},
        {"id":"22","nombre":"Id-Kort","formularioDonacion":"no"},
        {"id":"23","nombre":"RUC","formularioDonacion":"no"},
        {"id":"24","nombre":"Permiso de Protección Temporal","formularioDonacion":"no"}
      ]';

    $items = json_decode($tiposDeIdentificaciones);
    foreach ($items as $item) {
      TipoIdentificacion::create([
        'nombre' => $item->nombre,
        'formulario_donacion' => $item->formularioDonacion == 'si' ? true : false,
      ]);
    }
  }
}
