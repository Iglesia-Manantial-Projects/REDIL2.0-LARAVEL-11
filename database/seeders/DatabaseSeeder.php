<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Barrio;

use App\Models\Localidad;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call(RoleSeeder::class);
    $this->call(TipoUsuarioSeeder::class);
    $this->call(ConfiguracionSeeder::class);
    $this->call(ReporteBajaAltaSeeder::class);
    $this->call(TipoBajaAltaSeeder::class);
    $this->call(GrupoSeeder::class);
    $this->call(TipoServicioGrupoSeeder::class);
    $this->call(ServidorGrupoSeeder::class);
    $this->call(ServicioServidorGrupoSeeder::class);
    $this->call(FormularioUsuarioSeeder::class);
    $this->call(IglesiaSeeder::class);
    $this->call(SedeSeeder::class);
    $this->call(TipoSedeSeeder::class);
    $this->call(TipoGrupoSeeder::class);
    $this->call(GrupoExcluidoSeeder::class);
    $this->call(RangoEdadSeeder::class);
    $this->call(EstadoCivilSeeder::class);
    $this->call(TipoVinculacionSeeder::class);
    $this->call(PasoCrecimientoSeeder::class);
    $this->call(CrecimientoUsuarioSeeder::class);
    $this->call(OcupacionSeeder::class);
    $this->call(NivelAcademicoSeeder::class);
    $this->call(EstadoNivelAcademicoSeeder::class);
    $this->call(ProfesionSeeder::class);
    $this->call(CampoInformeExcelSeeder::class);
    $this->call(CampoExtraSeeder::class);
    $this->call(TipoIdentificacionSeeder::class);
    $this->call(TipoSangreSeeder::class);
    $this->call(SectorEconomicoSeeder::class);
    $this->call(TipoViviendaSeeder::class);
    $this->call(PaisSeeder::class);
    $this->call(TipoParentescoSeeder::class);
    $this->call(ParienteUsuarioSeeder::class);
    $this->call(ReporteReunionSeeder::class);
    $this->call(ReporteGrupoSeeder::class);
    $this->call(PeticionSeeder::class);
    $this->call(TipoPeticionSeeder::class);
    $this->call(ContinenteSeeder::class);
    $this->call(DepartamentoSeeder::class);
    $this->call(RegionSeeder::class);
    $this->call(MunicipioSeeder::class);
    $this->call(LocalidadSeeder::class);
    $this->call(BarrioSeeder::class);
    $this->call(TipoFormatoDireccionSeeder::class);
    $this->call(EstadoPasoCrecimientoUsuarioSeeder::class);
    $this->call(TipoAsignacionSeeder::class);
    $this->call(AutomatizacionPasoCrecimientoSeeder::class);
    $this->call(TemaSeeder::class);
    $this->call(CategoriaTemaSeeder::class);
    $this->call(CampoExtraGrupoSeeder::class);
    $this->call(ReporteGrupoBajaAltaSeeder::class);
    $this->call(TemaCategoriaSeeder::class);
    $this->call(SeccionPasoCrecimientoSeeder::class);

  }
}
