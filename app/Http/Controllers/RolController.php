<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class RolController extends Controller
{
  public function gestionar(): View
  {
    return view('contenido.paginas.roles-privilegios.gestionar-roles-privilegios');
  }
}
