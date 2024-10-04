<?php

namespace App\Livewire\RolesPrivilegios;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class GestionarRolesPrivilegios extends Component
{
  public $busqueda = '';
  public $hola = '..';

  // Variables del modal
  public $tituloModalPermisos = '',
    $iconoModalPermisos = '',
    $msnModalPermisos = '',
    $formularioModalPermisos = '',
    $idRolUpdatePermisos = '',
    $arrayPermisosRol = [];

  // Formulario nuevo
  #[Validate('required')]
  public $nombreRol = '';
  public $idRol = '';
  public $iconoRol = '';
  #[Validate('nullable|numeric')]
  public $lista_asistentes_sede_id;
  #[Validate('nullable|numeric')]
  public $lista_grupos_sede_id;
  #[Validate('nullable|numeric')]
  public $lista_reportes_grupo_sede_id;
  #[Validate('nullable|numeric')]
  public $lista_reuniones_sede_id;
  #[Validate('nullable|numeric')]
  public $lista_sedes_sede_id;
  #[Validate('nullable|numeric')]
  public $lista_ingresos_sede_id;
  #[Validate('nullable|numeric')]
  public $lista_peticiones_sede_id;
  #[Validate('nullable|numeric')]
  public $ver_sumatoria_ingresos_reportes_grupo_id;

  protected $listeners = ['cerrarModal', 'abrirModal', 'updatePermiso', 'eliminarRol'];

  public function abrirFormularioActualizarPermisos($rolId, $accion)
  {
    $rol = Role::find($rolId);
    $this->idRolUpdatePermisos = $rol->id;
    $this->tituloModalPermisos = $rol->name;
    $this->iconoModalPermisos = $rol->icono;
    $this->msnModalPermisos = '';
    $this->hola = $accion;
    $bloques = $this->bloquesDePermisos();
    $this->formularioModalPermisos = '';

    $this->hola = $rol;
    $this->arrayPermisosRol = $rol->permissions->pluck('name')->toArray();

    $this->dispatch('abrirModal', nombreModal: 'editarPermisosAlRol');
  }

  public function abrirFormularioAddRol()
  {
    $this->resetErrorBag(); // Establece los mensajes de error en la validacion
    $this->limpiarFormulario();
    $this->dispatch('abrirModal', nombreModal: 'addRol');
  }

  public function abrirFormularioEditarRol($rolId)
  {
    $this->resetErrorBag(); // Establece los mensajes de error en la validacion
    $rol = Role::find($rolId);
    $this->idRol = $rolId;
    $this->nombreRol = $rol->name;
    $this->iconoRol = $rol->icono;
    $this->lista_asistentes_sede_id = $rol->lista_asistentes_sede_id;
    $this->lista_grupos_sede_id = $rol->lista_grupos_sede_id;
    $this->lista_reportes_grupo_sede_id = $rol->lista_reportes_grupo_sede_id;
    $this->lista_reuniones_sede_id = $rol->lista_reuniones_sede_id;
    $this->lista_sedes_sede_id = $rol->lista_sedes_sede_id;
    $this->lista_ingresos_sede_id = $rol->lista_ingresos_sede_id;
    $this->lista_peticiones_sede_id = $rol->lista_peticiones_sede_id;
    $this->ver_sumatoria_ingresos_reportes_grupo_id = $rol->ver_sumatoria_ingresos_reportes_grupo_id;

    $this->dispatch('abrirModal', nombreModal: 'editarRol');
  }

  public function updatePermiso($rolId, $permisoId)
  {
    $this->msnModalPermisos = '$permiso->name';
    $rol = Role::find($rolId);
    $permiso = Permission::find($permisoId);
    if ($rol->hasPermissionTo($permiso->name)) {
      // Elimina el permiso
      $rol->revokePermissionTo($permiso->name);
      $this->msnModalPermisos =
        'El permiso <b>"' . str_replace('_', ' ', $permiso->titulo) . '"</b> fue revocado con éxito.';
    } else {
      // Agrega el permiso
      $rol->givePermissionTo($permiso->name);
      $this->msnModalPermisos =
        'El permiso <b>"' . str_replace('_', ' ', $permiso->titulo) . '"</b> fue asignado con éxito.';
    }
  }

  public function nuevoRol()
  {
    $this->validate();

    $this->iconoRol ? ($icono = $this->iconoRol) : ($icono = 'ti ti-user-question');

    $existeRol = Role::where('name', $this->nombreRol)->count();
    if ($existeRol) {
      $this->dispatch('cerrarModal', nombreModal: 'addRol');
      $this->dispatch(
        'msn',
        msnIcono: 'warning',
        msnTitulo: '¡Ups!',
        msnTexto: 'El rol ' . $this->nombreRol . ' ya existe, por favor intenta de nuevo.'
      );
    } else {
      $nuevoRol = Role::create([
        'name' => $this->nombreRol,
        'icono' => $icono,
        'lista_asistentes_sede_id' => is_numeric($this->lista_asistentes_sede_id)
          ? $this->lista_asistentes_sede_id
          : null,
        'lista_grupos_sede_id' => is_numeric($this->lista_grupos_sede_id) ? $this->lista_grupos_sede_id : null,
        'lista_reportes_grupo_sede_id' => is_numeric($this->lista_reportes_grupo_sede_id)
          ? $this->lista_reportes_grupo_sede_id
          : null,
        'lista_reuniones_sede_id' => is_numeric($this->lista_reuniones_sede_id) ? $this->lista_reuniones_sede_id : null,
        'lista_sedes_sede_id' => is_numeric($this->lista_sedes_sede_id) ? $this->lista_sedes_sede_id : null,
        'lista_ingresos_sede_id' => is_numeric($this->lista_ingresos_sede_id) ? $this->lista_ingresos_sede_id : null,
        'lista_peticiones_sede_id' => is_numeric($this->lista_peticiones_sede_id) ? $this->lista_peticiones_sede_id : null,
        'ver_sumatoria_ingresos_reportes_grupo_id' => is_numeric($this->ver_sumatoria_ingresos_reportes_grupo_id)
          ? $this->ver_sumatoria_ingresos_reportes_grupo_id
          : null,
      ]);

      $this->dispatch('cerrarModal', nombreModal: 'addRol');
      $this->dispatch(
        'msn',
        msnIcono: 'success',
        msnTitulo: '¡Buen trabajo!',
        msnTexto: 'El rol ' . $nuevoRol->name . ', se creo con éxito.'
      );
    }
  }

  public function editarRol($rolId)
  {
    $this->validate();
    $existeRol = Role::where('id', '!=', $rolId)
      ->where('name', $this->nombreRol)
      ->count();

    if ($existeRol) {
      $this->dispatch('cerrarModal', nombreModal: 'addRol');
      $this->dispatch(
        'msn',
        msnIcono: 'warning',
        msnTitulo: '¡Ups!',
        msnTexto: 'El rol ' . $this->nombreRol . ' ya existe, por favor intenta actualizarlo con un nombre distinto.'
      );
    } else {
      $this->iconoRol ? ($icono = $this->iconoRol) : ($icono = 'ti ti-user-question');
      $rol = Role::where('id', $rolId)->first();
      $rol->name = $this->nombreRol;
      $rol->icono = $icono;

      $rol->lista_asistentes_sede_id = is_numeric($this->lista_asistentes_sede_id)
        ? $this->lista_asistentes_sede_id
        : null;
      $rol->lista_grupos_sede_id = is_numeric($this->lista_grupos_sede_id) ? $this->lista_grupos_sede_id : null;
      $rol->lista_reportes_grupo_sede_id = is_numeric($this->lista_reportes_grupo_sede_id)
        ? $this->lista_reportes_grupo_sede_id
        : null;
      $rol->lista_reuniones_sede_id = is_numeric($this->lista_reuniones_sede_id)
        ? $this->lista_reuniones_sede_id
        : null;
      $rol->lista_sedes_sede_id = is_numeric($this->lista_sedes_sede_id) ? $this->lista_sedes_sede_id : null;
      $rol->lista_ingresos_sede_id = is_numeric($this->lista_ingresos_sede_id) ? $this->lista_ingresos_sede_id : null;
      $rol->lista_peticiones_sede_id = is_numeric($this->lista_peticiones_sede_id) ? $this->lista_peticiones_sede_id : null;
      $rol->ver_sumatoria_ingresos_reportes_grupo_id = is_numeric($this->ver_sumatoria_ingresos_reportes_grupo_id)
        ? $this->ver_sumatoria_ingresos_reportes_grupo_id
        : null;

      $rol->save();

      $this->dispatch('cerrarModal', nombreModal: 'editarRol');
      $this->dispatch(
        'msn',
        msnIcono: 'success',
        msnTitulo: '¡Buen trabajo!',
        msnTexto: 'El rol ' . $rol->name . ', se actualizo con éxito.'
      );
    }
  }

  public function duplicarRol($rolId)
  {
    $rol = Role::where('id', $rolId)->first();
    $existeRol = Role::where('id', '!=', $rolId)
      ->where('name', $this->nombreRol)
      ->count();

    $nuevoRol = Role::create([
      'name' => $rol->name . ' copia' . $existeRol + 1,
      'icono' => $rol->icono,
      'lista_asistentes_sede_id' => $rol->lista_asistentes_sede_id,
      'lista_grupos_sede_id' => $rol->lista_grupos_sede_id,
      'lista_reportes_grupo_sede_id' => $rol->lista_reportes_grupo_sede_id,
      'lista_reuniones_sede_id' => $rol->lista_reuniones_sede_id,
      'lista_sedes_sede_id' => $rol->lista_sedes_sede_id,
      'lista_ingresos_sede_id' => $rol->lista_ingresos_sede_id,
      'lista_peticiones_sede_id' => $rol->lista_peticiones_sede_id,
      'ver_sumatoria_ingresos_reportes_grupo_id' => $rol->ver_sumatoria_ingresos_reportes_grupo_id,
    ]);
    $nuevoRol->syncPermissions($rol->permissions->pluck('name'));

    $this->dispatch(
      'msn',
      msnIcono: 'success',
      msnTitulo: '¡Buen trabajo!',
      msnTexto: 'El rol ' . $rol->name . ', fue duplicado con éxito.'
    );
  }

  public function eliminarRol($rolId)
  {
    $rol = Role::where('id', $rolId)->first();
    $rol->syncPermissions(['']);

    $rol->delete();
  }

  public function bloquesDePermisos()
  {
    $bloques = [];

    $item = new \stdClass();
    $item->nombre = 'Personas';
    $item->etiqueta = 'personas.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Grupos';
    $item->etiqueta = 'grupos.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Reportes grupos';
    $item->etiqueta = 'reportes_grupos.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Reuniones';
    $item->etiqueta = 'reuniones.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Reporte reuniones';
    $item->etiqueta = 'reporte_reuniones.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Sedes';
    $item->etiqueta = 'sedes.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Ingresos';
    $item->etiqueta = 'ingresos.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Informes';
    $item->etiqueta = 'informes.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Temas';
    $item->etiqueta = 'temas.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Iglesia';
    $item->etiqueta = 'iglesia.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Actividades';
    $item->etiqueta = 'actividades.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Puntos de pago';
    $item->etiqueta = 'puntos_de_pago.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Peticiones';
    $item->etiqueta = 'peticiones.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Padres';
    $item->etiqueta = 'padres.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Escuelas';
    $item->etiqueta = 'escuelas.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Familiar';
    $item->etiqueta = 'familiar.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Dashboard';
    $item->etiqueta = 'dashboard.';
    $bloques[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Administracion';
    $item->etiqueta = 'administracion.';
    $bloques[] = $item;

    return $bloques;
  }

  public function limpiarFormulario()
  {
    $this->idRol = '';
    $this->nombreRol = '';
    $this->iconoRol = '';
    $this->lista_asistentes_sede_id = '';
    $this->lista_grupos_sede_id = '';
    $this->lista_reportes_grupo_sede_id = '';
    $this->lista_reuniones_sede_id = '';
    $this->lista_sedes_sede_id = '';
    $this->lista_ingresos_sede_id = '';
    $this->lista_peticiones_sede_id = '';
    $this->ver_sumatoria_ingresos_reportes_grupo_id = '';
  }

  public function render()
  {
    $roles = Role::whereRaw("translate(name,'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ','aeiouAEIOUaeiouAEIOU') ILIKE '%$this->busqueda%'")
      ->orderBy('name', 'ASC')
      ->get();

    $checkboxes = [];
    $bloques = $this->bloquesDePermisos();

    foreach ($bloques as $bloque) {
      $permisos = Permission::whereRaw(
        "translate(name,'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ','aeiouAEIOUaeiouAEIOU') ILIKE '%" . $bloque->etiqueta . "%'"
      )->get();
      $item = new \stdClass();
      $item->bloque = $bloque;
      $item->permisos = $permisos;
      $checkboxes[] = $item;
    }

    return view('livewire.roles-privilegios.gestionar-roles-privilegios', [
      'roles' => $roles,
      'checkboxes' => json_encode($checkboxes),
    ]);
  }
}
