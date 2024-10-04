<?php

namespace App\Livewire\Usuarios;

use App\Helpers\Helpers;
use App\Models\Configuracion;
use App\Models\InformeGrupo;
use App\Models\User;
use App\Models\TipoAsignacion;
use App\Models\Grupo;
use \stdClass;

use Illuminate\Support\Facades\Mail;
use App\Mail\DefaultMail;
use App\Models\ServidorGrupo;
use App\Models\TipoServicioGrupo;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;


class UsuariosParaBusqueda extends Component
{
    public $id,
    $class,
    $label,
    $placeholder, // placeholder del campo input
    $obligatorio = false, // si es TRUE coloca el asterisco para indicar que el input es obligatorio
    $queUsuariosCargar, // Para saber si carga 'todos' los usuarios o los 'discipulos'
    $tipoBuscador, // Parametro para saber si es de tipo = lista, multiple o unico.
    $conDadosDeBaja, // Para saber si carga con los dados de baja o solo los dados de alta.
    $modulo, // esta variable nos ayuda identificar de que modulo es para programar su comportamiento en particular, por ejemplo si es para seleccionar los encargados de un grupo.
    $usuarioSeleccionadoId, // Cuando el tipoBuscador = unico va a precargar este usuario
    $usuariosSeleccionadosIds = [], // Cuando el tipoBuscador = multiple va precargar estos usuarios
    $grupo = null, // Puedo enviar por parametro el grupo, necesario por ejemplo para añadir un encargado
    $estiloSeleccion = null, // Si es null es el estilo basico que es (foto, nombre y tipo de usuario)
    $redirect=null,// si existe re dirije a una ruta especifica

    // --- Parametros de vinculación y desvinculación ---//
    $tieneInformeDeVinculacion = false, // Si es TRUE, una vez seleccionado el grupo debe de abrir el modal para escribir el informe de vinculación al grupo
    $tieneInformeDeDesvinculacion = false, // Si es TRUE, una vez se de en el btn de eliminar el grupo debe de abrir el modal para escribir el informe de desvinculación al grupo
    $validarPrivilegiosTipoGrupo = false, // Si es TRUE verifica si el ROL activo tiene el privilegio de asignar/desvincular grupo según el tipo de grupo

    // --- Parametros privilegio para asignar o desvincular
    $tiposGruposNoPrivilegioAsignar = [],
    $tiposGruposNoPrivilegioDesvincular = [],
    $cantUsuariosCargados = 3;

    public  $busqueda = '',
    $configuracion,
    $usuarioSeleccionado = null, // Variable donde se guardara el usuario seleccionado cuando el tipoBuscador = unico
    $usuariosSeleccionados = [], // Variable donde se guardaran los usuarios seleccionados cuando el tipoBuscador = multiple
    $verListaBusqueda = false,
    $verInputBusqueda = true,
    $tipoAsignacionDefault = null,
    $tiposAsignaciones = null,
    $tiposDesvinculacion = null,
    $tiposServicioGrupo = null,
    $informeId,
    $gruposDondeAsisteActualmente = [];

    // Variables motivoModalAsignacion
   // #[Validate('required')]
    public $motivoModalAsignacion;
    public $observacionModalAsigancion;
    public $desvinculacionDeServiciosModalAsignacion;
    public $idsDesviculacionDeLosGruposModalAsignacion = []; //Tambien usado en modalConfirmacionDesviculacionGruposDondeAsiste

    // Variables modalInformeDesvinculacion
    //#[Validate('required')]
    public $motivoModalDesvinculacion;
    public $observacionModalDesvinculacion;
    public $desvinculacionDeServiciosModalDesvinculacion;

    // Variables modalGestionarServicios
    public $modalUserId; //Tambien usado en modalConfirmacionDesviculacionGruposDondeAsiste
    public $idsServiciosUsuario = [];

    public $rolActivo;

    protected $listeners = ['cargarMas'];

    public function mount()
    {
      $this->configuracion = Configuracion::find(1);
      $this->rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();

      if ($this->tipoBuscador == 'multiple') {
        if ($this->usuariosSeleccionadosIds) {
          $this->seleccionarUsuarios();
        }
      } elseif ($this->tipoBuscador == 'unico') {
        if ($this->usuarioSeleccionadoId) {
          $this->seleccionarUsuario($this->usuarioSeleccionadoId);
        }
      }

      if ($this->validarPrivilegiosTipoGrupo) {

        if($this->modulo == 'encargados-grupo')
        {
          $this->tiposGruposNoPrivilegioAsignar = $this->rolActivo->privilegiosTiposGrupo()
          ->wherePivot("asignar_encargado", "=", FALSE)
          ->select('tipo_grupos.id')
          ->pluck('tipo_grupos.id')
          ->toArray();

          $this->tiposGruposNoPrivilegioDesvincular = $this->rolActivo->privilegiosTiposGrupo()
            ->wherePivot("desvincular_encargado", "=", FALSE)
            ->select('tipo_grupos.id')
            ->pluck('tipo_grupos.id')
            ->toArray();
        }

        if($this->modulo == 'integrantes-grupo')
        {
          $this->tiposGruposNoPrivilegioAsignar = $this->rolActivo->privilegiosTiposGrupo()
          ->wherePivot("asignar_asistente", "=", FALSE)
          ->select('tipo_grupos.id')
          ->pluck('tipo_grupos.id')
          ->toArray();

          $this->tiposGruposNoPrivilegioDesvincular = $this->rolActivo->privilegiosTiposGrupo()
            ->wherePivot("desvincular_asistente", "=", FALSE)
            ->select('tipo_grupos.id')
            ->pluck('tipo_grupos.id')
            ->toArray();
        }

      }

      if ($this->tieneInformeDeVinculacion || $this->tieneInformeDeDesvinculacion) {

        $this->tipoAsignacionDefault = TipoAsignacion::where('default', true)->first();
        if($this->modulo == 'encargados-grupo')
        {
          $this->tiposAsignaciones = TipoAsignacion::where('para_asignar_lideres', true)->get();
          $this->tiposDesvinculacion = TipoAsignacion::where('para_desvincular_lideres', true)->get();
        }

        if($this->modulo == 'integrantes-grupo')
        {
          $this->tiposAsignaciones = TipoAsignacion::where('para_asignar_asistentes', true)->get();
          $this->tiposDesvinculacion = TipoAsignacion::where('para_desvincular_asistentes', true)->get();
        }
      }

      if($this->modulo == 'servidores-grupo')
      {
        $this->tiposServicioGrupo =  TipoServicioGrupo::get();
      }

    }

    public function seleccionarUsuarios($usuarioId = null)
    {
      $agregar = true;
      $msnTexto = 'No tienes los privilegios suficientes para desvincular a la persona de este grupo. Por favor, consulte a su administrador.';


      if ($usuarioId) {
        $user = User::withTrashed()->find($usuarioId);

        if ($this->tiposGruposNoPrivilegioAsignar)
          $agregar = in_array($this->grupo->tipoGrupo->id, $this->tiposGruposNoPrivilegioAsignar) ? false : true;

        // Validacíon adicional de integrantes-grupo
        if($this->modulo == 'integrantes-grupo' && !$this->rolActivo->hasPermissionTo('grupos.privilegio_asignar_asistente_todo_tipo_asistente_a_un_grupo'))
        {
          $tipoGrupo = $this->grupo->tipoGrupo;
          $usuarioPermintidos = $tipoGrupo->tipoUsuariosPermitidos()
          ->wherePivot('para_asistentes', '=', TRUE)
          ->where('tipo_usuario_id','=',$user->tipo_usuario_id)
          ->count();

          if( $usuarioPermintidos <= 0 )
          {
            $agregar = false;
            $msnTexto = 'No es posible asignar un <b>'.$user->tipoUsuario->nombre.'</b> a un grupo tipo <b>'.$this->grupo->tipoGrupo->nombre.'</b>. Por favor, consulte a su administrador.';
          }
        }

        if ($agregar) {
          if (in_array($usuarioId, $this->usuariosSeleccionadosIds) == false) {

            // Aquí se programa el comportamiento unico que tiene a la hora de agregar un encargado a un grupo
            if($this->modulo == 'encargados-grupo')
            {
              //asigno el encargado al grupo
              $this->grupo->asignarEncargado($usuarioId);

              // Si el tipo de usuario tiene permiso se envia mensaje de bienvenida al encargado
              if($this->grupo->tipoGrupo->enviar_mensaje_bienvenida)
              {
                $mailData = new stdClass();
                $mailData->subject = 'Bienvenido al liderazgo';
                $mailData->nombre = $user->nombre(3);
                $mailData->mensaje = $this->grupo->tipoGrupo->mensaje_bienvenida;

                Mail::to('softjuancarlos@gmail.com')->send(new DefaultMail($mailData));
              }

              // creo la bitacora por defecto
              if ($this->tieneInformeDeVinculacion) {

                  // Creo en informe de vinculación default
                  $informe = InformeGrupo::create([
                    'user_id' => $user->id,
                    'grupo_id' => $this->grupo->id,
                    'observaciones' => '',
                    'tipo_asignacion_id' => $this->tipoAsignacionDefault->id,
                    'tipo_informe' => 1, // (1) "Asignación de líder"
                    'user_autor_asignacion' => auth()->user()->id
                  ]);

                  // Abro el modal si tiene el privilegio
                  if ($this->rolActivo->hasPermissionTo('grupos.mostar_modal_informe_asignacion_de_lideres')) {
                    $this->informeId = $informe->id;
                    $this->resetErrorBag(); // Establece los mensajes de error en la validacion
                    $this->dispatch('abrirModal', nombreModal: 'modalInformeAsignacion');
                  }
              }
            }

            // Aquí se programa el comportamiento unico que tiene a la hora de agregar un servidor a un grupo
            if($this->modulo == 'servidores-grupo')
            {
              ServidorGrupo::create([
                'user_id' => $user->id,
                'grupo_id' => $this->grupo->id
              ]);
            }

            // Aquí se programa el comportamiento unico que tiene a la hora de agregar un integrante al grupo
            if($this->modulo == 'integrantes-grupo')
            {
              // lo asigno al grupo
              $user->cambiarGrupo($this->grupo->id);

              // creo la bitacora por defecto
              if ($this->tieneInformeDeVinculacion) {

                // Creo en informe de vinculación default
                $informe = InformeGrupo::create([
                  'user_id' => $user->id,
                  'grupo_id' => $this->grupo->id,
                  'observaciones' => '',
                  'tipo_asignacion_id' => $this->tipoAsignacionDefault->id,
                  'tipo_informe' => 2, // (2) "Asignación de asistente"
                  'user_autor_asignacion' => auth()->user()->id
                ]);


                $this->gruposDondeAsisteActualmente = $user->gruposDondeAsiste()->where('grupos.id', '!=', $this->grupo->id)
                ->select('grupos.id','grupos.nombre')->get();

                // Abro el modal si tiene el privilegio
                if ($this->rolActivo->hasPermissionTo('grupos.mostar_modal_informe_asignacion_de_asistentes')) {
                  $this->informeId = $informe->id;
                  $this->resetErrorBag(); // Establece los mensajes de error en la validacion
                  $this->dispatch('abrirModal', nombreModal: 'modalInformeAsignacion');
                }elseif( count( $this->gruposDondeAsisteActualmente ) > 0){
                  $this->modalUserId = $user->id;
                  $this->dispatch('abrirModal', nombreModal: 'modalConfirmacionDesviculacionGruposDondeAsiste');
                }
              }
            }

            array_push($this->usuariosSeleccionadosIds, $usuarioId);
            $this->usuariosSeleccionadosIds = array_unique($this->usuariosSeleccionadosIds);
            $users = User::whereIn('id', $this->usuariosSeleccionadosIds)->get();
            $this->cantUsuariosCargados = 3;
            $this->usuariosSeleccionados = $users;
          }
        } else {
          $this->dispatch(
            'msn',
            msnIcono: 'warning',
            msnTitulo: '¡Ups!',
            msnTexto: $msnTexto
          );
        }
      } else {
        $users = User::whereIn('id', $this->usuariosSeleccionadosIds)->get();
        $this->cantUsuariosCargados = 3;
        $this->usuariosSeleccionados = $users;
      }

      $this->verListaBusqueda = false;
    }

    // Cuando el tipoBuscador == 'unico'
    public function seleccionarUsuario($usuarioId)
    {
      $agregar = true;
      $user = User::withTrashed()->find($usuarioId);

      if ($agregar) {
        $this->cantUsuariosCargados = 3;
        $this->usuarioSeleccionado = $user;

        if($this->estiloSeleccion == 'pequeno')
        {
          $this->verInputBusqueda = false;
        }
        $this->verListaBusqueda = false;
      } else {
        $this->dispatch(
          'msn',
          msnIcono: 'warning',
          msnTitulo: '¡Ups!',
          msnTexto: 'No tienes los privilegios suficientes. Por favor, consulte a su administrador.'
        );
      }
    }

    public function quitarSeleccion($usuarioId = null)
    {
      $quitar = true;
      $msnTexto = 'No tienes los privilegios suficientes para desvincular a la persona de este grupo. Por favor, consulte a su administrador.';

      if ($usuarioId) {
        $user = User::withTrashed()->find($usuarioId);
        if ($this->tiposGruposNoPrivilegioDesvincular)
        {
          $quitar = (in_array($this->grupo->tipoGrupo->id, $this->tiposGruposNoPrivilegioDesvincular)) ? false : true;
        }
      }

      // Validacíon adicional de encargados-grupo
      if($this->modulo == 'encargados-grupo')
      {
        if ($this->rolActivo->hasPermissionTo('personas.ajax_obtiene_asistentes_solo_ministerio')) {

          if ($usuarioId ==  auth()->user()->id)
          {
            $quitar = false;
            $msnTexto = 'No puedes eliminarte de tu propio grupo. Por favor, consulte a su administrador.';
          }
        }
      }

      if ($quitar) {

         // Aquí se programa el comportamiento unico que tiene a la hora de eliminar un encargado de un grupo
         if($this->modulo == 'encargados-grupo')
         {
           //elimino el encargado al grupo
           $this->grupo->eliminarEncargado($usuarioId);

           // creo la bitacora por defecto
           if ($this->tieneInformeDeVinculacion) {

               // Creo en informe de vinculación default
               $informe = InformeGrupo::create([
                 'user_id' => $user->id,
                 'grupo_id' => $this->grupo->id,
                 'observaciones' => '',
                 'tipo_asignacion_id' => $this->tipoAsignacionDefault->id,
                 'tipo_informe' => 3, // (3) "Desvinculacion de líder"
                 'user_autor_asignacion' => auth()->user()->id
               ]);

               // Abro el modal si tiene el privilegio
               if ($this->rolActivo->hasPermissionTo('grupos.mostar_modal_informe_desvinculacion_de_lideres')) {
                 $this->informeId = $informe->id;
                 $this->dispatch('abrirModal', nombreModal: 'modalInformeDesvinculacion');
               }
           }
         }

         // Aquí se programa el comportamiento unico que tiene a la hora de eliminar un servidor de un grupo
         if($this->modulo == 'servidores-grupo')
         {
           $servidorAEliminar= ServidorGrupo::where("user_id","=", $usuarioId)->where("grupo_id", "=", $this->grupo->id)->first();
		   		 $servidorAEliminar->tipoServicioGrupo()->detach();
           $servidorAEliminar->delete();
         }

        // Aquí se programa el comportamiento unico que tiene a la hora de eliminar un integrante del grupo
        if($this->modulo == 'integrantes-grupo')
        {
          // lo desvinculo al grupo
          $user->desvincularDeGrupo($this->grupo->id);

          // creo la bitacora por defecto
          if ($this->tieneInformeDeVinculacion) {

            // Creo en informe de vinculación default
            $informe = InformeGrupo::create([
              'user_id' => $user->id,
              'grupo_id' => $this->grupo->id,
              'observaciones' => '',
              'tipo_asignacion_id' => $this->tipoAsignacionDefault->id,
              'tipo_informe' => 4, // (4) "Desvinculacion de asistente"
              'user_autor_asignacion' => auth()->user()->id
            ]);

            // Abro el modal si tiene el privilegio
            if ($this->rolActivo->hasPermissionTo('grupos.mostar_modal_informe_desvinculacion_de_asistentes')) {
              $this->informeId = $informe->id;
              $this->dispatch('abrirModal', nombreModal: 'modalInformeDesvinculacion');
            }
          }

        }

        if( $this->tipoBuscador == 'multiple') {

          $this->usuariosSeleccionadosIds = array_values(array_diff($this->usuariosSeleccionadosIds, array($usuarioId)));
          $this->usuariosSeleccionadosIds = array_unique($this->usuariosSeleccionadosIds);
          $users = User::whereIn('id', $this->usuariosSeleccionadosIds)->get();
          $this->usuariosSeleccionados = $users;
        }elseif( $this->tipoBuscador == 'unico'){
          $this->usuarioSeleccionadoId = '';
          $this->usuarioSeleccionado = '';
        }

        if($this->redirect && !$this->usuarioSeleccionadoId)
        {
          $this->redirectRoute($this->redirect);
        }



      } else {
        $this->dispatch(
          'msn',
          msnIcono: 'warning',
          msnTitulo: '¡Ups!',
          msnTexto: $msnTexto
        );
      }

      if($this->estiloSeleccion == 'pequeno')
      {
        $this->verInputBusqueda = true;
        $this->verListaBusqueda = true;
      }
    }

    public function desplegarListaBusqueda()
    {
      $this->verListaBusqueda = true;
    }

    public function ocultarListaBusqueda()
    {
      $this->verListaBusqueda = false;
    }

    public function resetCantidadUsuariosCargados()
    {
      $this->cantUsuariosCargados = 3;
    }

    public function cargarMas()
    {
      $this->cantUsuariosCargados += 1;
    }

    // submit de modal informe asignacion
    public function informeAsignacion()
    {
      $this->validate( ['motivoModalAsignacion' => 'required'] );
      $informe = InformeGrupo::find($this->informeId);
      $informe->tipo_asignacion_id = $this->motivoModalAsignacion;
      $informe->observaciones = $this->observacionModalAsigancion;

      if ($this->desvinculacionDeServiciosModalAsignacion){

        //Si marca que si elimino todos los servicios que tenga en los diferentes grupos
        $servidoresGrupo= ServidorGrupo::where("user_id","=", $informe->user_id)->get();

				foreach($servidoresGrupo as $servidorGrupo)
				{
					$servidorGrupo->tipoServicioGrupo()->detach();
		      $servidorGrupo->delete();
				}
      }

      $this->desvinculacionGruposAnteriores($informe->user_id);


      $informe->save();
      $this->dispatch('cerrarModal', nombreModal: 'modalInformeAsignacion');
    }

    // submit de modal informe desvinculacion
    public function informeDesvinculacion()
    {
      $this->validate( ['motivoModalDesvinculacion' => 'required'] );
      $informe = InformeGrupo::find($this->informeId);
      $informe->tipo_asignacion_id = $this->motivoModalDesvinculacion;
      $informe->observaciones = $this->observacionModalDesvinculacion;

      if ($this->desvinculacionDeServiciosModalDesvinculacion){

        //Si marca que si elimino todos los servicios que tenga en los diferentes grupos
        $servidoresGrupo= ServidorGrupo::where("user_id","=", $informe->user_id)->get();

				foreach($servidoresGrupo as $servidorGrupo)
				{
					$servidorGrupo->tipoServicioGrupo()->detach();
		      $servidorGrupo->delete();
				}
      }
      $informe->save();
      $this->dispatch('cerrarModal', nombreModal: 'modalInformeDesvinculacion');
    }

    public function gestionarServicios($userId)
    {
      $user = User::find($userId);
      $this->idsServiciosUsuario = $user->serviciosPrestadosEnGrupos($this->grupo->id)->pluck('id')->toArray();

      $this->modalUserId = $userId;
      $this->dispatch('abrirModal', nombreModal: 'modalGestionarServicios');
    }

    public function guardarServicios()
    {
      $servidor = ServidorGrupo::where("user_id","=", $this->modalUserId)->where("grupo_id", "=", $this->grupo->id)->first();
      $servidor->tipoServicioGrupo()->sync($this->idsServiciosUsuario);
      $this->dispatch('cerrarModal', nombreModal: 'modalGestionarServicios');
    }

    public function desvinculacionGruposAnteriores($usuarioId)
    {
      if(count($this->idsDesviculacionDeLosGruposModalAsignacion) > 0)
      {
        $user = User::find($usuarioId);
        foreach($this->idsDesviculacionDeLosGruposModalAsignacion as $grupoDesvinculacionId)
        {
          $user->desvincularDeGrupo($grupoDesvinculacionId);
        }
      }

      $this->dispatch('cerrarModal', nombreModal: 'modalConfirmacionDesviculacionGruposDondeAsiste');
    }

    public function render()
    {
      if($this->queUsuariosCargar == "todos")
      {
        $usuarios = User::withTrashed()
        ->leftJoin('integrantes_grupo', 'users.id', '=', 'integrantes_grupo.user_id')
        ->select('users.*', 'integrantes_grupo.grupo_id as grupo_id')
        ->get()
        ->unique('id');
      }elseif($this->queUsuariosCargar == 'discipulos'){
        $usuarios = auth()
        ->user()
        ->discipulos('todos');
      }elseif($this->queUsuariosCargar == 'grupo'){
        $usuarios = User::withTrashed()
        ->leftJoin('integrantes_grupo', 'users.id', '=', 'integrantes_grupo.user_id')
        ->where('integrantes_grupo.grupo_id', $this->grupo->id)
        ->select('users.*', 'integrantes_grupo.grupo_id as grupo_id')
        ->get()
        ->unique('id');
      }

      if($this->conDadosDeBaja == 'no')
      {
        $usuarios = $usuarios->whereNull('deleted_at');
      }

      if($this->tipoBuscador == 'multiple')
      {
        $usuarios = $usuarios->whereNotIn('id', $this->usuariosSeleccionadosIds);
      } elseif($this->tipoBuscador == 'unico')
      {
        $usuarios = $usuarios->whereNotIn('id', [$this->usuarioSeleccionadoId]);
      }



      if ($this->busqueda) {
        $buscar = htmlspecialchars($this->busqueda);
        $buscar = Helpers::sanearStringConEspacios($buscar);
        $buscar = str_replace(["'"], '', $buscar);
        $buscar_array = explode(' ', $buscar);

        foreach ($buscar_array as $palabra) {
          $usuarios = $usuarios->filter(function ($usuario) use ($palabra) {
              $respuesta  = false !== stristr(Helpers::sanearStringConEspacios($usuario->primer_nombre), $palabra) ||
              false !== stristr(Helpers::sanearStringConEspacios($usuario->segundo_nombre), $palabra) ||
              false !== stristr(Helpers::sanearStringConEspacios($usuario->primer_apellido), $palabra) ||
              false !== stristr(Helpers::sanearStringConEspacios($usuario->segundo_apellido), $palabra) ||
              false !== stristr(Helpers::sanearStringConEspacios($usuario->identificacion), $palabra) ||
              false !== stristr(Helpers::sanearStringConEspacios($usuario->email), $palabra) ||
              false !== stristr(Helpers::sanearStringConEspacios($usuario->id), $palabra);

              return $respuesta;
          });
        }
      }

      if ($usuarios->count() > 0) {
        $usuarios = $usuarios->toQuery()->orderBy('id','desc')->paginate($this->cantUsuariosCargados);
      } else {
        $usuarios = User::whereRaw('1=2')->paginate(1);
      }

      return view('livewire.usuarios.usuarios-para-busqueda', ['usuarios' => $usuarios]);
    }
}
