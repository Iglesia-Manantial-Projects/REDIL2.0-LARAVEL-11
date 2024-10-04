<!DOCTYPE html>

<html><head><title>Mi QR</title>
      <meta http-equiv="Content-Type" content="text/pdf; charset=utf-8"/>
      <style type="text/css">
      body {
        font-family: "Helvetica"
      }
      td{margin:0px !important;}
      .code{
        height: 60px !important;
      }

      .center {
        margin-left: auto;
        margin-right: auto;
      }

      html{margin:20px !important;padding:10px !important}
      .mayusculas{text-transform: uppercase;}

      </style></head><body style="margin:0px !important;">

      <table class="center">
      <tr>
        <td style="padding: 5px; padding-rigth: 10px">
          <center>
            <img src="{{ $foto }}" style="width: 130px; height: 130px;">
            <p>
              <b> <span style="font-size: 20px;">{{ $usuario->tipoUsuario->nombre }}</span></b>
              <br> {{ $usuario->nombre(4) }}
              <br> <b>{{ $usuario->tipoIdentificacion ? $usuario->tipoIdentificacion->nombre.':' : '' }}</b> {{ $usuario->identificacion ? $usuario->identificacion : '' }}
            </p>
          </center>
        </td>
        <td style="padding: 5px; padding-left: 10px">
          <center>
            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($usuario->id.'', 'QRCODE') }}" style="width: 200px; height: 200px;"  alt="barcode"/>
          </center>
        </td>
      </tr>
      </table>
  </body>
</html>
