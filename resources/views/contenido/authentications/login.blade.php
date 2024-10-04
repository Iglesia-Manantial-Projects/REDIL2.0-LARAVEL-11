
@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', 'Login')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
@endsection

@section('content')
<div class="authentication-wrapper authentication-cover authentication-bg">
  <div class="authentication-inner row">
    <!-- /Left Text -->
    <div class="d-none d-lg-flex col-lg-7 p-0">
      <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center"  style="background-image: url({{ asset('assets/img/illustrations/bg-redil.jpg') }}); background-size: cover;">

      </div>
    </div>
    <!-- /Left Text -->

    <!-- Login -->
    <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
      <div class="w-px-400 mx-auto">
        <!-- Logo -->
        <div class="app-brand demo d-flex justify-content-center ">
          <a href="{{url('/')}}" class="app-brand-link gap-0 d-flex align-self-end">
            <span class="app-brand-logo demo">
              @include('_partials.macros',["height"=>"50px", "width"=>"50px", "fill"=> "#3772e4" ])
            </span>
            <span class="app-brand-text m-0 menu-text fw-bold h1 pt-3">{{config('variables.templateName')}}</span>
          </a>
        </div>
        <!-- /Logo -->
        <h3 class=" mb-1 d-none">{{config('variables.templateName')}}</h3>
        <p class="mb-4 text-center text-muted p-0" style="letter-spacing:  .2rem;">Pastoreo Inteligente</p>

        <form id="" class="mb-3" action="{{ route('login') }}" method="POST">
          @csrf
          @if( $errors->first() )

          <div class="alert alert-danger alert-dismissible" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          @endif

          <div class="mb-3">
            <label for="email" class="form-label d-none">Email or Username</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Email" autofocus>
          </div>
          <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
              <label class="form-label d-none" for="password">Password</label>
            </div>
            <div class="input-group input-group-merge">
              <input type="password" id="password" class="form-control" name="password" placeholder="Contraseña" aria-describedby="password" />
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
            <a href="{{url('auth/forgot-password-cover')}}">
              <small>¿Olvide mi contraseña?</small>
            </a>
          </div>
          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember-me">
              <label class="form-check-label" for="remember-me">
                Remember Me
              </label>
            </div>
          </div>
          <button class="btn btn-primary d-grid w-100">
            Ingresar
          </button>
        </form>

        <p class="text-center">
          <span>¿Eres nuevo?</span>
          @foreach($formularios as $formulario)
          <a href="{{ route('usuario.nuevo', $formulario) }}">
            <span>{{ $formulario->nombre }} </span>
          </a>
          @endforeach
        </p>

        <div class="divider my-4">
          <div class="divider-text">Ingresar con</div>
        </div>

        <div class="d-flex justify-content-center">
          <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">
            <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
          </a>

          <a href="javascript:;" class="btn btn-icon btn-label-google-plus">
            <i class="tf-icons fa-brands fa-google fs-5"></i>
          </a>
        </div>
      </div>
    </div>
    <!-- /Login -->
  </div>
</div>
@endsection
