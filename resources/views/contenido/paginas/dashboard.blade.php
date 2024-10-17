@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Inicio')

@section('content')
<h4>Home Page {{ auth()->user()->primer_nombre }}</h4>
<p>For more layout options refer <a href="{{ config('variables.documentation') ? config('variables.documentation').'/laravel-introduction.html' : '#' }}" target="_blank" rel="noopener noreferrer">documentation</a>.</p>

{{ auth()->user()->roles()->select('name')->get() }}
<br> rol dependiente actual
{{auth()->user()->roles()
                  ->wherePivot('dependiente', '=', true)
                  ->first()}}
<br>
{{ auth()->user()->informacion_opcional }}
<br>
Tipos de usuario


<br><br>
                  {{ App\Models\TipoUsuario::select('nombre','id_rol_dependiente','puntaje')->get()}}

  <br><br>
  Roles
  {{ App\Models\Role::select('id','name')->get()}}
  <br><br>
  {{ App\Models\AutomatizacionPasoCrecimiento::get()}}

@endsection
