<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Configuracion;

use App\Models\Actividad;

class ActividadController extends Controller
{
    //

    public function crear()
    {
        $configuracion=Configuracion::find(1);

        return $configuracion;
        return view('contenido.paginas.actividades.nueva',[

            'configuracion'=>$configuracion,

            ]);
    }
}
