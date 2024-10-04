@extends('layouts/layoutMaster')

@section('title', 'Tema - Nuevo')

@section('vendor-style')

<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<style>
      .card-body{
            color:black !important;
      }
</style>

@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
<!-- este meta es obligatorio para el tinymce que es el editor de texto -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>
<!-- este script es obligatorio para el tinymce que es el editor de texto -->


@endsection

@section('content')
<h4 class="mb-1">Tema: {{$tema->titulo}}</h4>
<p class="mb-4"></p>

@include('layouts.status-msn')
      <div class="row m-0 card">
            <img  src="@if($tema->portada != '' ){{ Storage::url($configuracion->ruta_almacenamiento.'/temas/archivos/'.$tema->portada)}} @endif" alt="Banner image" class="img-fluid p-0 rounded-top">       
          <div class="row d-flex p-5 card-body">           
            <h5 style="color:black !important;" class="card-title fw-bold fs-2 mt-5  " ><?php echo $tema->titulo;?> </h5>
               <?php echo $tema->contenido;?>      
          </div>
      </div>

@endsection
