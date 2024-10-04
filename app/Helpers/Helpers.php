<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class Helpers
{
  public static function appClasses()
  {

    $data = config('custom.custom');


    // default data array
    $DefaultData = [
      'myLayout' => 'vertical',
      'myTheme' => 'theme-default',
      'myStyle' => 'light',
      'myRTLSupport' => true,
      'myRTLMode' => true,
      'hasCustomizer' => true,
      'showDropdownOnHover' => true,
      'displayCustomizer' => true,
      'contentLayout' => 'compact',
      'headerType' => 'fixed',
      'navbarType' => 'fixed',
      'menuFixed' => true,
      'menuCollapsed' => false,
      'footerFixed' => false,
      'customizerControls' => [
        'rtl',
        'style',
        'headerType',
        'contentLayout',
        'layoutCollapsed',
        'showDropdownOnHover',
        'layoutNavbarOptions',
        'themes',
      ],
      //   'defaultLanguage'=>'en',
    ];

    // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
    $data = array_merge($DefaultData, $data);

    // All options available in the template
    $allOptions = [
      'myLayout' => ['vertical', 'horizontal', 'blank', 'front'],
      'menuCollapsed' => [true, false],
      'hasCustomizer' => [true, false],
      'showDropdownOnHover' => [true, false],
      'displayCustomizer' => [true, false],
      'contentLayout' => ['compact', 'wide'],
      'headerType' => ['fixed', 'static'],
      'navbarType' => ['fixed', 'static', 'hidden'],
      'myStyle' => ['light', 'dark', 'system'],
      'myTheme' => ['theme-default', 'theme-bordered', 'theme-semi-dark'],
      'myRTLSupport' => [true, false],
      'myRTLMode' => [true, false],
      'menuFixed' => [true, false],
      'footerFixed' => [true, false],
      'customizerControls' => [],
      // 'defaultLanguage'=>array('en'=>'en','fr'=>'fr','de'=>'de','ar'=>'ar'),
    ];

    //if myLayout value empty or not match with default options in custom.php config file then set a default value
    foreach ($allOptions as $key => $value) {
      if (array_key_exists($key, $DefaultData)) {
        if (gettype($DefaultData[$key]) === gettype($data[$key])) {
          // data key should be string
          if (is_string($data[$key])) {
            // data key should not be empty
            if (isset($data[$key]) && $data[$key] !== null) {
              // data key should not be exist inside allOptions array's sub array
              if (!array_key_exists($data[$key], $value)) {
                // ensure that passed value should be match with any of allOptions array value
                $result = array_search($data[$key], $value, 'strict');
                if (empty($result) && $result !== 0) {
                  $data[$key] = $DefaultData[$key];
                }
              }
            } else {
              // if data key not set or
              $data[$key] = $DefaultData[$key];
            }
          }
        } else {
          $data[$key] = $DefaultData[$key];
        }
      }
    }
    $styleVal = $data['myStyle'] == "dark" ? "dark" : "light";
    $styleUpdatedVal = $data['myStyle'] == "dark" ? "dark" : $data['myStyle'];
    // Determine if the layout is admin or front based on cookies
    $layoutName = $data['myLayout'];
    $isAdmin = Str::contains($layoutName, 'front') ? false : true;

    $modeCookieName = $isAdmin ? 'admin-mode' : 'front-mode';
    $colorPrefCookieName = $isAdmin ? 'admin-colorPref' : 'front-colorPref';

    // Determine style based on cookies, only if not 'blank-layout'
    if ($layoutName !== 'blank') {
      if (isset($_COOKIE[$modeCookieName])) {
        $styleVal = $_COOKIE[$modeCookieName];
        if ($styleVal === 'system') {
          $styleVal = isset($_COOKIE[$colorPrefCookieName]) ? $_COOKIE[$colorPrefCookieName] : 'light';
          }
        $styleUpdatedVal = $_COOKIE[$modeCookieName];
      }
    }

    isset($_COOKIE['theme']) ? $themeVal = $_COOKIE['theme'] : $themeVal = $data['myTheme'];

    $directionVal = isset($_COOKIE['direction']) ? ($_COOKIE['direction'] === "true" ? 'rtl' : 'ltr') : $data['myRTLMode'];

    //layout classes
    $layoutClasses = [
      'layout' => $data['myLayout'],
      'theme' => $themeVal,
      'themeOpt' => $data['myTheme'],
      'style' => $styleVal,
      'styleOpt' => $data['myStyle'],
      'styleOptVal' => $styleUpdatedVal,
      'rtlSupport' => $data['myRTLSupport'],
      'rtlMode' => $data['myRTLMode'],
      'textDirection' => $directionVal,//$data['myRTLMode'],
      'menuCollapsed' => $data['menuCollapsed'],
      'hasCustomizer' => $data['hasCustomizer'],
      'showDropdownOnHover' => $data['showDropdownOnHover'],
      'displayCustomizer' => $data['displayCustomizer'],
      'contentLayout' => $data['contentLayout'],
      'headerType' => $data['headerType'],
      'navbarType' => $data['navbarType'],
      'menuFixed' => $data['menuFixed'],
      'footerFixed' => $data['footerFixed'],
      'customizerControls' => $data['customizerControls'],
    ];

    // sidebar Collapsed
    if ($layoutClasses['menuCollapsed'] == true) {
      $layoutClasses['menuCollapsed'] = 'layout-menu-collapsed';
    }

    // Header Type
    if ($layoutClasses['headerType'] == 'fixed') {
      $layoutClasses['headerType'] = 'layout-menu-fixed';
    }
    // Navbar Type
    if ($layoutClasses['navbarType'] == 'fixed') {
      $layoutClasses['navbarType'] = 'layout-navbar-fixed';
    } elseif ($layoutClasses['navbarType'] == 'static') {
      $layoutClasses['navbarType'] = '';
    } else {
      $layoutClasses['navbarType'] = 'layout-navbar-hidden';
    }

    // Menu Fixed
    if ($layoutClasses['menuFixed'] == true) {
      $layoutClasses['menuFixed'] = 'layout-menu-fixed';
    }


    // Footer Fixed
    if ($layoutClasses['footerFixed'] == true) {
      $layoutClasses['footerFixed'] = 'layout-footer-fixed';
    }

    // RTL Supported template
    if ($layoutClasses['rtlSupport'] == true) {
      $layoutClasses['rtlSupport'] = '/rtl';
    }

    // RTL Layout/Mode
    if ($layoutClasses['rtlMode'] == true) {
      $layoutClasses['rtlMode'] = 'rtl';
      $layoutClasses['textDirection'] = isset($_COOKIE['direction']) ? ($_COOKIE['direction'] === "true" ? 'rtl' : 'ltr') : 'rtl';

    } else {
      $layoutClasses['rtlMode'] = 'ltr';
      $layoutClasses['textDirection'] = isset($_COOKIE['direction']) && $_COOKIE['direction'] === "true" ? 'rtl' : 'ltr';

    }

    // Show DropdownOnHover for Horizontal Menu
    if ($layoutClasses['showDropdownOnHover'] == true) {
      $layoutClasses['showDropdownOnHover'] = true;
    } else {
      $layoutClasses['showDropdownOnHover'] = false;
    }

    // To hide/show display customizer UI, not js
    if ($layoutClasses['displayCustomizer'] == true) {
      $layoutClasses['displayCustomizer'] = true;
    } else {
      $layoutClasses['displayCustomizer'] = false;
    }

    return $layoutClasses;
  }

  public static function updatePageConfig($pageConfigs)
  {
    $demo = 'custom';
    if (isset($pageConfigs)) {
      if (count($pageConfigs) > 0) {
        foreach ($pageConfigs as $config => $val) {
          Config::set('custom.' . $demo . '.' . $config, $val);
        }
      }
    }
  }
  
  
  function bloquesDePermisos()
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

    /*
    .
    .
    .
    .
    .
    .
    .
    .
    .
    puntos_de_pago.
    peticiones.
    padres.
    escuelas.
    familiar.
    dashboard.
    administracion.
    */

    return $bloques;
  }

  public static function camposPeticiones()
  {

    $camposPeticiones = [];

    $item = new \stdClass();
    $item->id = 1;
    $item->value = 'tipo_peticion_id';
    $item->nombre = 'Tipo de petición';
    $camposPeticiones[] = $item;

    $item = new \stdClass();
    $item->id = 2;
    $item->value = 'estado';
    $item->nombre = 'Estado de la petición';
    $camposPeticiones[] = $item;

    $item = new \stdClass();
    $item->id = 3;
    $item->value = 'descripcion';
    $item->nombre = 'Descripción petición';
    $camposPeticiones[] = $item;

    $item = new \stdClass();
    $item->id = 4;
    $item->value = 'respuesta';
    $item->nombre = 'Respuesta petición';
    $camposPeticiones[] = $item;

    $item = new \stdClass();
    $item->id = 5;
    $item->value = 'fecha';
    $item->nombre = 'Fecha petición';
    $camposPeticiones[] = $item;

    $item = new \stdClass();
    $item->id = 6;
    $item->value = 'autor_creacion_id';
    $item->nombre = 'Autor creación';
    $camposPeticiones[] = $item;

    $item = new \stdClass();
    $item->id = 7;
    $item->value = 'pais_id';
    $item->nombre = 'Pais petición';
    $camposPeticiones[] = $item;

    return $camposPeticiones;
  }

  public static function camposRelacionesUsuarios()
  {
    $camposRelaciones = [];

   

    $item = new \stdClass();
    $item->id = 1;
    $item->value = 'pariente_user_id';
    $item->nombre = 'Pariente Secundario';
    $camposRelaciones[] = $item;

    $item = new \stdClass();
    $item->id = 2;
    $item->value = 'es_el_responsable';
    $item->nombre = 'Soy Responsable?';
    $camposRelaciones[] = $item;

    $item = new \stdClass();
    $item->id = 3;
    $item->value = 'tipo_pariente_id';
    $item->nombre = 'Parentesco';
    $camposRelaciones[] = $item;
   
    return $camposRelaciones;
  }


  public static function estadoPeticion($estado)
  {
    if($estado == 1){
      $respuesta = 'Sin responder';
    }elseif($estado == 2){
      $respuesta = 'Finalizada';
    }elseif($estado == 3){
      $respuesta = 'En seguimiento';
    }

    return $respuesta;
  }

  public static function eliminarTildes($cadena)
  {
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðòóôõöøùúûýýþÿ';
    $modificadas = 'aaaaaaaceeeeiiiidoooooouuuuybsaaaaaaaceeeeiiiidoooooouuuyyby';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    return utf8_encode($cadena);
  }

  /** * Reemplaza todos los acentos por sus equivalentes sin ellos * * @param $string * string la cadena a sanear * * @return $string * string saneada */
  public static function sanearString($string)
  {
    $string = trim($string);
    $string = str_replace(
      ['á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'],
      ['a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'],
      $string
    );
    $string = str_replace(['é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'], ['e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'], $string);
    $string = str_replace(['í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'], ['i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'], $string);
    $string = str_replace(['ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'], ['o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'], $string);
    $string = str_replace(['ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'], ['u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'], $string);
    $string = str_replace(['ñ', 'Ñ', 'ç', 'Ç'], ['n', 'N', 'c', 'C'], $string);
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
      [
        '\\',
        '¨',
        'º',
        '-',
        '~',
        '#',
        '@',
        '|',
        '!',
        "\"",
        '·',
        "$",
        '%',
        '&',
        '/',
        '(',
        ')',
        '?',
        "'",
        '¡',
        '¿',
        '[',
        '^',
        '`',
        ']',
        '+',
        '}',
        '{',
        '¨',
        '´',
        '>“, “< ',
        ';',
        ',',
        ':',
        '.',
        ' ',
      ],
      '',
      $string
    );
    return $string;
  }

  public static function sanearStringConEspacios($string)
  {
    $string = trim($string);
    $string = str_replace(
      ['á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'],
      ['a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'],
      $string
    );
    $string = str_replace(['é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'], ['e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'], $string);
    $string = str_replace(['í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'], ['i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'], $string);
    $string = str_replace(['ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'], ['o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'], $string);
    $string = str_replace(['ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'], ['u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'], $string);
    $string = str_replace(['ñ', 'Ñ', 'ç', 'Ç'], ['n', 'N', 'c', 'C'], $string);
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
      [
        '\\',
        '¨',
        'º',
        '-',
        '~',
        '#',
        '@',
        '|',
        '!',
        "\"",
        '·',
        "$",
        '%',
        '&',
        '/',
        '(',
        ')',
        '?',
        "'",
        '¡',
        '¿',
        '[',
        '^',
        '`',
        ']',
        '+',
        '}',
        '{',
        '¨',
        '´',
        '>“, “< ',
        ';',
        ',',
        ':',
        '.',
      ],
      '',
      $string
    );
    return $string;
  }

  public static function diasDeLaSemana()
  {
    $dias = [];

    $item = new \stdClass();
    $item->nombre = 'Lunes';
    $item->id = '2';
    $dias[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Martes';
    $item->id = '3';
    $dias[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Miércoles';
    $item->id = '4';
    $dias[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Jueves';
    $item->id = '5';
    $dias[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Viernes';
    $item->id = '6';
    $dias[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Sábado';
    $item->id = '7';
    $dias[] = $item;

    $item = new \stdClass();
    $item->nombre = 'Domingo';
    $item->id = '1';
    $dias[] = $item;

    return $dias;
  }

  public static function obtenerDiaDeLaSemana($dia)
  {
    $respuesta = '';
    switch ($dia) {
      case 1:
        $respuesta = 'Domingo';
        break;
      case 2:
        $respuesta = 'Lunes';
        break;
      case 3:
        $respuesta = 'Martes';
        break;
      case 4:
        $respuesta = 'Miércoles';
        break;
      case 5:
        $respuesta = 'Jueves';
        break;
      case 6:
        $respuesta = 'Viernes';
        break;
      case 7:
        $respuesta = 'Sábado';
        break;
    }

    return $respuesta;
  }

  public static function libros()
  {
      $libros = '[
          {"id":"1","nombre":"GÉNESIS", "capitulos":"50", "seudonimo": "GÉNESIS"},
          {"id":"2","nombre":"ÉXODO", "capitulos":"40", "seudonimo": "ÉXODO"},
          {"id":"3","nombre":"LEVÍTICO", "capitulos":"27", "seudonimo": "LEVÍTICO"},
          {"id":"4","nombre":"NÚMEROS", "capitulos":"36", "seudonimo": "NÚMEROS"},
          {"id":"5","nombre":"DEUTERONOMIO", "capitulos":"34", "seudonimo": "DEUTERONOMIO"},
          {"id":"6","nombre":"JOSUÉ", "capitulos":"24", "seudonimo": "JOSUÉ"},
          {"id":"7","nombre":"JUECES", "capitulos":"21", "seudonimo": "JUECES"},
          {"id":"8","nombre":"RUT", "capitulos":"4", "seudonimo": "RUT"},
          {"id":"9","nombre":"1 SAMUEL", "capitulos":"31", "seudonimo": "1 SAMUEL"},
          {"id":"10","nombre":"2 SAMUEL", "capitulos":"24", "seudonimo": "2 SAMUEL"},
          {"id":"11","nombre":"1 REYES", "capitulos":"22", "seudonimo": "1 REYES"},
          {"id":"12","nombre":"2 REYES", "capitulos":"25", "seudonimo": "2 REYES"},
          {"id":"13","nombre":"1 CRÓNICAS", "capitulos":"29", "seudonimo": "1 CRÓNICAS"},
          {"id":"14","nombre":"2 CRÓNICAS", "capitulos":"36", "seudonimo": "2 CRÓNICAS"},
          {"id":"15","nombre":"ESDRAS", "capitulos":"10", "seudonimo": "ESDRAS"},
          {"id":"16","nombre":"NEHEMÍAS", "capitulos":"13", "seudonimo": "NEHEMÍAS"},
          {"id":"17","nombre":"ESTER", "capitulos":"10", "seudonimo": "ESTER"},
          {"id":"18","nombre":"JOB", "capitulos":"42", "seudonimo": "JOB"},
          {"id":"19","nombre":"SALMOS", "capitulos":"150", "seudonimo": "SALMOS"},
          {"id":"20","nombre":"PROVERBIOS", "capitulos":"31", "seudonimo": "PROVERBIOS"},
          {"id":"21","nombre":"ECLESIASTÉS", "capitulos":"12", "seudonimo": "ECLESIASTÉS"},
          {"id":"22","nombre":"CANTARES", "capitulos":"8", "seudonimo": "CANTARES"},
          {"id":"23","nombre":"ISAÍAS", "capitulos":"66", "seudonimo": "ISAÍAS"},
          {"id":"24","nombre":"JEREMÍAS", "capitulos":"52", "seudonimo": "JEREMÍAS"},
          {"id":"25","nombre":"LAMENTACIONES", "capitulos":"5", "seudonimo": "LAMENTACIONES"},
          {"id":"26","nombre":"EZEQUIEL", "capitulos":"48", "seudonimo": "EZEQUIEL"},
          {"id":"27","nombre":"DANIEL", "capitulos":"12", "seudonimo": "DANIEL"},
          {"id":"28","nombre":"OSEAS", "capitulos":"14", "seudonimo": "OSEAS"},
          {"id":"29","nombre":"JOEL", "capitulos":"3", "seudonimo": "JOEL"},
          {"id":"30","nombre":"AMÓS", "capitulos":"9", "seudonimo": "AMÓS"},
          {"id":"31","nombre":"ABDÍAS", "capitulos":"1", "seudonimo": "ABDÍAS"},
          {"id":"32","nombre":"JONÁS", "capitulos":"4", "seudonimo": "JONÁS"},
          {"id":"33","nombre":"MIQUEAS", "capitulos":"7", "seudonimo": "MIQUEAS"},
          {"id":"34","nombre":"NAHÚM", "capitulos":"3", "seudonimo": "NAHUM"},
          {"id":"35","nombre":"HABACUC", "capitulos":"3", "seudonimo": "HABACUC"},
          {"id":"36","nombre":"SOFONÍAS", "capitulos":"3", "seudonimo": "SOFONÍAS"},
          {"id":"37","nombre":"HAGEO", "capitulos":"2", "seudonimo": "HAGEO"},
          {"id":"38","nombre":"ZACARÍAS", "capitulos":"14", "seudonimo": "ZACARÍAS"},
          {"id":"39","nombre":"MALAQUÍAS", "capitulos":"4", "seudonimo": "MALAQUÍAS"},
          {"id":"40","nombre":"MATEO", "capitulos":"28", "seudonimo": "MATEO"},
          {"id":"41","nombre":"MARCOS", "capitulos":"16", "seudonimo": "MARCOS"},
          {"id":"42","nombre":"LUCAS", "capitulos":"24", "seudonimo": "LUCAS"},
          {"id":"43","nombre":"JUAN", "capitulos":"21", "seudonimo": "JUAN"},
          {"id":"44","nombre":"HECHOS", "capitulos":"28", "seudonimo": "HECHOS"},
          {"id":"45","nombre":"ROMANOS", "capitulos":"16", "seudonimo": "ROMANOS"},
          {"id":"46","nombre":"1 CORINTIOS", "capitulos":"16", "seudonimo": "1CORINTIOS"},
          {"id":"47","nombre":"2 CORINTIOS", "capitulos":"13", "seudonimo": "2CORINTHIANS"},
          {"id":"48","nombre":"GÁLATAS", "capitulos":"6", "seudonimo": "GÁLATAS"},
          {"id":"49","nombre":"EFESIOS", "capitulos":"6", "seudonimo": "EFESIOS"},
          {"id":"50","nombre":"FILIPENSES", "capitulos":"4", "seudonimo": "FILIPENSES"},
          {"id":"51","nombre":"COLOSENSES", "capitulos":"4", "seudonimo": "COLOSENSES"},
          {"id":"52","nombre":"1 TESALONICENSES", "capitulos":"5", "seudonimo": "1 TESALONICENSES"},
          {"id":"53","nombre":"2 TESALONICENSES", "capitulos":"3", "seudonimo": "2 TESALONICENSES"},
          {"id":"54","nombre":"1 TIMOTEO", "capitulos":"6", "seudonimo": "1 TIMOTEO"},
          {"id":"55","nombre":"2 TIMOTEO", "capitulos":"4", "seudonimo": "2 TIMOTEO"},
          {"id":"56","nombre":"TITO", "capitulos":"3", "seudonimo": "TITO"},
          {"id":"57","nombre":"FILEMÓN", "capitulos":"1", "seudonimo": "FILEMÓN"},
          {"id":"58","nombre":"HEBREOS", "capitulos":"13", "seudonimo": "HEBREOS"},
          {"id":"59","nombre":"SANTIAGO", "capitulos":"5", "seudonimo": "SANTIAGO"},
          {"id":"60","nombre":"1 PEDRO", "capitulos":"5", "seudonimo": "1 PEDRO"},
          {"id":"61","nombre":"2 PEDRO", "capitulos":"3", "seudonimo": "2 PEDRO"},
          {"id":"62","nombre":"1 JUAN", "capitulos":"5", "seudonimo": "1JOHN"},
          {"id":"63","nombre":"2 JUAN", "capitulos":"1", "seudonimo": "2JOHN"},
          {"id":"64","nombre":"3 JUAN", "capitulos":"1", "seudonimo": "3JOHN"},
          {"id":"65","nombre":"JUDAS", "capitulos":"1", "seudonimo": "JUDAS"},
          {"id":"66","nombre":"APOCALIPSIS", "capitulos":"22", "seudonimo": "APOCALIPSIS"}
      ]';


      $array = json_decode($libros);

      return $array;
  }
}
