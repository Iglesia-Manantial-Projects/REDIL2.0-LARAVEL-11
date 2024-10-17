<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\PeticionController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\TemaController;
use App\Http\Controllers\ParienteUsuarioController;
use App\Http\Controllers\ActividadController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::get('/', function () {
  if (Auth::check()) {
    return view('contenido.paginas.dashboard');
  } else {
    return redirect()->route('login');
  }
});

Route::get('/dashboard', function () {
  return view('contenido.paginas.dashboard');
})
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

Route::get('/pagina-no-encontrada', function () {
  return view('contenido.paginas.pages-misc-error');
})->name('pagina-no-encontrada');


// Usuarios
Route::get('/usuario/{formulario}/nuevo', [UserController::class, 'nuevo'])->name('usuario.nuevo');
Route::post('/usuario/{formulario}/crear', [UserController::class, 'crear'])->name('usuario.crear');





Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  // Usuarios
  Route::get('/usuarios/{tipo?}', [UserController::class, 'listar'])->name('usuario.lista');
  Route::get('/usuario/{usuario}/perfil', [UserController::class, 'perfil'])->name('usuario.perfil');
  Route::get('/usuario/{usuario}/descargar-codigo-qr', [UserController::class, 'descargarCodigoQr'])->name('usuario.descargarCodigoQr');
  Route::get('/usuario/{formulario}/{usuario}/modificar', [UserController::class, 'modificar'])->name('usuario.modificar');
  Route::get('/usuario/{formulario?}/{usuario}/informacion-congregacional/{tipoUsuarioSugerido?}', [UserController::class,'informacionCongregacional',])->name('usuario.informacionCongregacional');
  Route::get('/usuario/{formulario?}/{usuario}/geo-asignacion', [UserController::class, 'geoAsignacion'])->name('usuario.geoAsignacion');

  Route::post('/usuarios/excel', [UserController::class, 'listadoFinalCsv'])->name('usuario.listadoFinalCsv');
  Route::post('/usuarios/{usuario}/cambiar-contrasena', [UserController::class, 'cambiarContrasena'])->name('usuario.cambiarContrasena');
  Route::post('/usuarios/{usuario}/cambiar-contrasena-default', [UserController::class, 'cambiarContrasenaDefault'])->name('usuario.cambiarContrasenaDefault');
  Route::patch('/usuario/{usuario}/informacion-congregacional', [UserController::class,'actualizarInformacionCongregacional',])->name('usuario.actualizarInformacionCongregacional');
  Route::patch('/usuario/{formulario}/{usuario}/editar', [UserController::class, 'editar'])->name('usuario.editar');

  // Grupos
  Route::get('/grupos/{tipo?}', [GrupoController::class, 'listar'])->name('grupo.lista');
  Route::get('/grupo/nuevo', [GrupoController::class, 'nuevo'])->name('grupo.nuevo');
  Route::get('/grupo/mapa-de-grupos', [GrupoController::class, 'mapaDeGrupos'])->name('grupo.mapaDeGrupos');
  Route::get('/grupo/grafico-del-ministerio/{idNodo?}/{maximosNiveles?}', [GrupoController::class, 'graficoDelMinisterio'])->name('grupo.graficoDelMinisterio');
  Route::get('/grupo/{grupo}/perfil', [GrupoController::class, 'perfil'])->name('grupo.perfil');
  Route::get('/grupo/{grupo}/modificar', [GrupoController::class, 'modificar'])->name('grupo.modificar');
  Route::get('/grupo/{grupo}/gestionar-encargados', [GrupoController::class, 'gestionarEncargados'])->name('grupo.gestionarEncargados');
  Route::get('/grupo/{grupo}/gestionar-integrantes', [GrupoController::class, 'gestionarIntegrantes'])->name('grupo.gestionarIntegrantes');
  Route::get('/grupo/{grupo}/georreferencia', [GrupoController::class, 'georreferencia'])->name('grupo.georreferencia');
  Route::get('/grupo/{grupo}/grafico-ministerial', [GrupoController::class, 'graficoMinisterial'])->name('grupo.graficoMinisterial');
  Route::get('/grupo/ver-exclusiones', [GrupoController::class, 'verExclusiones'])->name('grupo.verExclusiones');

  Route::post('/grupo/crear', [GrupoController::class, 'crear'])->name('grupo.crear');
  Route::post('/grupos/excel', [GrupoController::class, 'listadoFinalCsv'])->name('grupo.listadoFinalCsv');
  Route::post('/grupo/crear-exclusion', [GrupoController::class, 'crearExclusion'])->name('grupo.crearExclusion');
  Route::post('/grupo/{grupo}/excluir', [GrupoController::class, 'excluir'])->name('grupo.excluir');
  Route::patch('/grupo/{grupo}/editar', [GrupoController::class, 'editar'])->name('grupo.editar');
  Route::patch('/grupo/{tipo}/{id}/cambiar-indicie', [GrupoController::class, 'cambiarIndice'])->name('grupo.cambiarIndice');

  // Sedes
  Route::get('/sedes', [SedeController::class, 'listar'])->name('sede.lista');
  Route::get('/sede/nueva', [SedeController::class, 'nueva'])->name('sede.nueva');
  Route::get('/sede/{sede}/modificar', [SedeController::class, 'modificar'])->name('sede.modificar');
  Route::get('/sede/{sede}/perfil', [SedeController::class, 'perfil'])->name('sede.perfil');

  Route::post('/sede/crear', [SedeController::class, 'crear'])->name('sede.crear');
  Route::post('/sede/{sede}/eliminar', [SedeController::class, 'eliminar'])->name('sede.eliminar');
  Route::patch('/sede/{sede}/editar', [SedeController::class, 'editar'])->name('sede.editar');

  // Peticiones
  Route::get('/peticiones/panel-peticiones', [PeticionController::class, 'panel'])->name('peticion.panel');
  Route::get('/peticiones/gestionar/{tipo?}', [PeticionController::class, 'gestionar'])->name('peticion.gestionar');
  Route::get('/peticion/nueva', [PeticionController::class, 'nueva'])->name('peticion.nueva');
  Route::post('/peticion/crear', [PeticionController::class, 'crear'])->name('peticion.crear');
  Route::post('/peticion/{tipo}/eliminaciones', [PeticionController::class, 'eliminaciones'])->name('peticion.eliminaciones');
  Route::post('/peticion/{id}/eliminacion', [PeticionController::class, 'eliminacion'])->name('peticion.eliminacion');
  Route::post('/peticion/{tipo}/generar-excel', [PeticionController::class, 'generarExcel'])->name('peticion.generarExcel');

  // temas generales
  Route::get('/temas', [TemaController::class, 'listar'])->name('tema.lista');
  Route::get('/tema/nuevo', [TemaController::class, 'nuevo'])->name('tema.nuevo');
  Route::get('/tema/{tema}/ver', [TemaController::class, 'ver'])->name('tema.ver');
  Route::get('/tema/{tema}/actualizar', [TemaController::class, 'actualizar'])->name('tema.actualizar');
  Route::post('/tema/{tema}/eliminar', [TemaController::class, 'eliminar'])->name('tema.eliminar');
  Route::post('/tema/{tema}/update', [TemaController::class, 'update'])->name('tema.update');
  Route::post('/tema/crear', [TemaController::class, 'crear'])->name('tema.crear');
  Route::post('/tema/cargar', [TemaController::class, 'cargar'])->name('tema.cargar');

  //  relaciones familiares
  Route::get('/familias/gestionar/{userId?}', [ParienteUsuarioController::class, 'gestionar'])->name('familias.gestionar');
  Route::get('/familias/crear', [ParienteUsuarioController::class, 'crear'])->name('familias.crear');
  Route::get('/familias/informes', [ParienteUsuarioController::class, 'informes'])->name('familias.informes');
  Route::post('/familias/{pariente}/eliminar', [ParienteUsuarioController::class, 'eliminar'])->name('familias.eliminar');
  Route::post('/familias/generar-excel', [ParienteUsuarioController::class, 'generarExcel'])->name('familias.generarExcel');

  // actividades
  Route::get('/actividades/crear', [ActividadController::class, 'crear'])->name('actividades.crear');


  //Route::get('/', [HomePage::class, 'index'])->name('pages-home');
  Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

  // pages
  Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

  // authentication
  Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
  Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');

  // roles y privilegios
  Route::get('/configuracion/gestionar-roles', [RolController::class, 'gestionar'])->name(
    'configuracion.gestionar-roles'
  );



});

require __DIR__ . '/auth.php';
