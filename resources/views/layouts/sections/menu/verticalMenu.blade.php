@php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();

$rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
$formularios=$rolActivo->formularios()->where('privilegio','=','subitem_nuevo_asistente')->where('es_modal','=',FALSE)->get();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- ! Hide app brand if navbar-full -->
  @if(!isset($navbarFull))
  <div class="app-brand demo">
    <a href="{{url('/')}}" class="app-brand-link">
      <span class="app-brand-logo demo p-0">
        @include('_partials.macros',["height"=>"40px", "width"=>"40px", "fill"=> "#3772e4" ])
      </span>
      <span class="app-brand-text demo menu-text fw-bold fs-4 pt-3">{{config('variables.templateName')}}</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
      <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
    </a>
  </div>
  @endif

  <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Menú</span>
      </li>

      <li class="menu-item">
        <a href="{{ url('') }}" class="menu-link active">

          <i class="menu-icon tf-icons ti ti-smart-home"></i>
          <div>Inicio </div>
        </a>
      </li>

      <li class="menu-item {{ request()->routeIs('usuario.*') ? 'active open':'' }}">
        <a href="" class="menu-link menu-toggle ">
          <i class="menu-icon tf-icons ti ti-users"></i>
          <div>Personas </div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item {{ request()->routeIs('usuario.lista') ? 'active':'' }}">
            <a href="{{ route('usuario.lista') }}" class="menu-link">
              <div>Listado</div>
            </a>
          </li>
          @foreach($formularios as $formulario)
          <li class="menu-item">
            <a href="{{ route('usuario.nuevo', $formulario) }}"  class="menu-link">
              <div>{{$formulario->nombre2}}</div>
            </a>
          </li>
          @endforeach

        </ul>

      </li>

      <li class="menu-item">
        <a href="" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-home-heart"></i>
          <div>Familias </div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{ route('familias.gestionar') }}" class="menu-link">
              <div>Gestionar</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('familias.informes') }}" class="menu-link">
              <div>Informes</div>
            </a>
          </li>

        </ul>

      </li>

      <li class="menu-item {{ request()->routeIs('peticion.*') ? 'active open':'' }}">
        <a href="" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-notes"></i>
          <div>Peticiones </div>
        </a>

        <ul class="menu-sub">

          <li class="menu-item">
            <a href="{{ route('peticion.nueva') }}" class="menu-link">
              <div>Nueva</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('peticion.gestionar') }}" class="menu-link">
              <div>Gestionar peticiones</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('peticion.panel') }}" class="menu-link">
              <div>Panel peticiones</div>
            </a>
          </li>
        </ul>

      </li>

      <li class="menu-item {{ request()->routeIs('grupo.*') ? 'active open':'' }}">
        <a href="" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-users-group"></i>
          <div>Grupos </div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item {{ request()->routeIs('grupo.lista') ? 'active':'' }}">
            <a href="{{ route('grupo.lista') }}" class="menu-link">
              <div>Listado</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('grupo.nuevo') }}" class="menu-link">
              <div>Nuevo</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('grupo.graficoDelMinisterio') }}" class="menu-link">
              <div>Gráfico del ministerio</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('grupo.mapaDeGrupos') }}" class="menu-link">
              <div>Mapa de grupos</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('grupo.verExclusiones') }}" class="menu-link">
              <div>Excluir grupos</div>
            </a>
          </li>
        </ul>

      </li>

      <li class="menu-item">
        <a href="" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-building-church"></i>
          <div>Reuniones </div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="" class="menu-link">
              <div>Listado</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="" class="menu-link">
              <div>Nueva</div>
            </a>
          </li>
        </ul>

      </li>

      <li class="menu-item">
        <a href="" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-school"></i>
          <div>Escuelas </div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="" class="menu-link">
              <div>Listado</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="" class="menu-link">
              <div>Nueva</div>
            </a>
          </li>
        </ul>

      </li>

      <li class="menu-item {{ request()->routeIs('sede.*') ? 'active open':'' }}">
        <a href="" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-building"></i>
          <div>Sedes </div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item {{ request()->routeIs('sede.lista') ? 'active':'' }}">
            <a href="{{ route('sede.lista') }}" class="menu-link">
              <div>Listado</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('sede.nueva') }}" class="menu-link">
              <div>Nueva</div>
            </a>
          </li>

        </ul>

      </li>

      <li class="menu-item">
        <a href="" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-calendar-star"></i>
          <div>Actividades </div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="" class="menu-link">
              <div>Listado</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('actividades.crear') }}" class="menu-link">
              <div>Nueva</div>
            </a>
          </li>
        </ul>

      </li>

      <li class="menu-item">
        <a href="" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-building-store"></i>
          <div>Punto de pago </div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="" class="menu-link">
              <div>Listado</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="" class="menu-link">
              <div>Nueva</div>
            </a>
          </li>
        </ul>

      </li>


      <li class="menu-item">
        <a href="" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-cash-banknote"></i>
          <div>Ingresos </div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="" class="menu-link">
              <div>Listado</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="" class="menu-link">
              <div>Nueva</div>
            </a>
          </li>
        </ul>

      </li>

      <li class="menu-item">
        <a href="" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-blockquote"></i>
          <div>Temas </div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{ route('tema.lista') }}" class="menu-link">
              <div>Listado</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('tema.nuevo') }}" class="menu-link">
              <div>Nuevo</div>
            </a>
          </li>
        </ul>

      </li>



      <li class="menu-item">
        <a href="" class="menu-link">
          <i class="menu-icon tf-icons ti ti-report"></i>
          <div>Informes </div>
        </a>
      </li>

      <li class="menu-item {{ request()->routeIs('configuracion.*') ? 'active open':'' }}">
        <a href="" class="menu-link menu-toggle ">
          <i class="menu-icon tf-icons ti ti-settings"></i>
          <div>Configuración </div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item {{ request()->routeIs('configuracion.lista') ? 'active':'' }}">
            <a href="{{ route('configuracion.gestionar-roles')}}" class="menu-link">
              <div>Gestionar roles</div>
            </a>
          </li>
        </ul>

      </li>

      <li class="menu-item">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-link">
          <i class="menu-icon tf-icons ti ti-logout-2"></i>
          <div>Cerrar sesión </div>
        </a>
      </li>




    @if(1==0)
    @foreach ($menuData[0]->menu as $menu)
        {{-- adding active and open class if child is active --}}

        {{-- menu headers --}}
        @if (isset($menu->menuHeader))
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ $menu->menuHeader }}</span>
          </li>
        @else

        {{-- active menu method --}}
        @php
        $activeClass = null;
        $currentRouteName = Route::currentRouteName();

        if ($currentRouteName === $menu->slug) {
        $activeClass = 'active';
        }
        elseif (isset($menu->submenu)) {
        if (gettype($menu->slug) === 'array') {
        foreach($menu->slug as $slug){
        if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
        $activeClass = 'active open';
        }
        }
        }
        else{
        if (str_contains($currentRouteName,$menu->slug) and strpos($currentRouteName,$menu->slug) === 0) {
        $activeClass = 'active open';
        }
        }

        }
        @endphp

        {{-- main menu --}}
        <li class="menu-item {{$activeClass}}">
          <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
            @isset($menu->icon)
            <i class="{{ $menu->icon }}"></i>
            @endisset
            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
            @isset($menu->badge)
            <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>

            @endisset
          </a>

          {{-- submenu --}}
          @isset($menu->submenu)
          @include('layouts.sections.menu.submenu',['menu' => $menu->submenu])
          @endisset
        </li>
      @endif
    @endforeach
    @endif
  </ul>

  <ul   style="display:none"  class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)

      {{-- adding active and open class if child is active --}}

      {{-- menu headers --}}
      @if (isset($menu->menuHeader))
        <li class="menu-header small">
            <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
      @else

      {{-- active menu method --}}
      @php
      $activeClass = null;
      $currentRouteName = Route::currentRouteName();

      if ($currentRouteName === $menu->slug) {
        $activeClass = 'active';
      }
      elseif (isset($menu->submenu)) {
        if (gettype($menu->slug) === 'array') {
          foreach($menu->slug as $slug){
            if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
              $activeClass = 'active open';
            }
          }
        }
        else{
          if (str_contains($currentRouteName,$menu->slug) and strpos($currentRouteName,$menu->slug) === 0) {
            $activeClass = 'active open';
          }
        }
      }
      @endphp

      {{-- main menu --}}
      <li class="menu-item {{$activeClass}}">
        <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
          @isset($menu->icon)
            <i class="{{ $menu->icon }}"></i>
          @endisset
          <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
          @isset($menu->badge)
            <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
          @endisset
        </a>

        {{-- submenu --}}
        @isset($menu->submenu)
          @include('layouts.sections.menu.submenu',['menu' => $menu->submenu])
        @endisset
      </li>
      @endif
    @endforeach
  </ul>

</aside>
