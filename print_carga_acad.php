<?php require_once('classregistro.php');?>
<?php
/*evitar sql injection*/
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


	
	//parametros
	$nivel = $var_nivel;
	$periodo = $var_periodo;
	$ano = $var_ano;	
	$cedula = GetSQLValueString($_GET['cedula'],"int");
	settype($cedula, "integer");
	
	//coneccion
	$materias = new registro;
	$horarios = new registro;
	$conecta = new conexion;
	$conecta->abrir();

	//cargar QUERY de HORARIOS
	$SQL = "Select  Horas.Hora, Horas.Descripcion, Lunes.Materia||Decode(Lunes.Choque,1,' ','*') As Lun, Martes.Materia||Decode(Martes.Choque,1,' ','*') As Mar,
        Miercoles.Materia||Decode(Miercoles.Choque,1,' ','*') as Mie, Jueves.Materia||Decode(Jueves.Choque,1,' ','*') as Jue,
        Viernes.Materia||Decode(Viernes.Choque,1,' ','*') As Vie, Sabado.Materia||Decode(Sabado.Choque,1,' ','*') as Sab,
        Domingo.Materia||Decode(Domingo.Choque,1,' ','*') as Dom
  From Unimar.Horas,
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Planificacion.Asignatura||'/'||Planificacion.Turno||'-'||Planificacion.Seccion||'/'||PlanificacionHorarios.Aula) as Materia
         From Unimar.Profesores, Unimar.Planificacion, Unimar.PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$ano'
          and Planificacion.Periodo       = '$periodo'
          and Planificacion.NivelEstudios = '$nivel'
          and Planificacion.Capacidad     > 0
          and Planificacion.Ano           = PlanificacionHorarios.Ano
          and Planificacion.Periodo       = PlanificacionHorarios.Periodo
          and Planificacion.NivelEstudios = PlanificacionHorarios.NivelEstudios
          and Planificacion.Asignatura    = PlanificacionHorarios.Asignatura
          and Planificacion.Secuencia     = PlanificacionHorarios.Secuencia
          and Planificacion.Turno         = PlanificacionHorarios.Turno
          and Planificacion.Seccion       = PlanificacionHorarios.Seccion
          and PlanificacionHorarios.Aula  <> 'PV'
          and PlanificacionHorarios.Dia   = 1
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Lunes,
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Planificacion.Asignatura||'/'||Planificacion.Turno||'-'||Planificacion.Seccion||'/'||PlanificacionHorarios.Aula) as Materia
         From Unimar.Profesores, Unimar.Planificacion, Unimar.PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$ano'
          and Planificacion.Periodo       = '$periodo'
          and Planificacion.NivelEstudios = '$nivel'
          and Planificacion.Capacidad     > 0
          and Planificacion.Ano           = PlanificacionHorarios.Ano
          and Planificacion.Periodo       = PlanificacionHorarios.Periodo
          and Planificacion.NivelEstudios = PlanificacionHorarios.NivelEstudios
          and Planificacion.Asignatura    = PlanificacionHorarios.Asignatura
          and Planificacion.Secuencia     = PlanificacionHorarios.Secuencia
          and Planificacion.Turno         = PlanificacionHorarios.Turno
          and Planificacion.Seccion       = PlanificacionHorarios.Seccion
          and PlanificacionHorarios.Aula  <> 'PV'
          and PlanificacionHorarios.Dia   = 2
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Martes,
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Planificacion.Asignatura||'/'||Planificacion.Turno||'-'||Planificacion.Seccion||'/'||PlanificacionHorarios.Aula) as Materia
         From Unimar.Profesores, Unimar.Planificacion, Unimar.PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$ano'
          and Planificacion.Periodo       = '$periodo'
          and Planificacion.NivelEstudios = '$nivel'
          and Planificacion.Capacidad     > 0
          and Planificacion.Ano           = PlanificacionHorarios.Ano
          and Planificacion.Periodo       = PlanificacionHorarios.Periodo
          and Planificacion.NivelEstudios = PlanificacionHorarios.NivelEstudios
          and Planificacion.Asignatura    = PlanificacionHorarios.Asignatura
          and Planificacion.Secuencia     = PlanificacionHorarios.Secuencia
          and Planificacion.Turno         = PlanificacionHorarios.Turno
          and Planificacion.Seccion       = PlanificacionHorarios.Seccion
          and PlanificacionHorarios.Aula  <> 'PV'
          and PlanificacionHorarios.Dia   = 3
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Miercoles,
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Planificacion.Asignatura||'/'||Planificacion.Turno||'-'||Planificacion.Seccion||'/'||PlanificacionHorarios.Aula) as Materia
         From Unimar.Profesores, Unimar.Planificacion, Unimar.PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$ano'
          and Planificacion.Periodo       = '$periodo'
          and Planificacion.NivelEstudios = '$nivel'
          and Planificacion.Capacidad     > 0
          and Planificacion.Ano           = PlanificacionHorarios.Ano
          and Planificacion.Periodo       = PlanificacionHorarios.Periodo
          and Planificacion.NivelEstudios = PlanificacionHorarios.NivelEstudios
          and Planificacion.Asignatura    = PlanificacionHorarios.Asignatura
          and Planificacion.Secuencia     = PlanificacionHorarios.Secuencia
          and Planificacion.Turno         = PlanificacionHorarios.Turno
          and Planificacion.Seccion       = PlanificacionHorarios.Seccion
          and PlanificacionHorarios.Aula  <> 'PV'
          and PlanificacionHorarios.Dia   = 4
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Jueves,
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Planificacion.Asignatura||'/'||Planificacion.Turno||'-'||Planificacion.Seccion||'/'||PlanificacionHorarios.Aula) as Materia
         From Unimar.Profesores, Unimar.Planificacion, Unimar.PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$ano'
          and Planificacion.Periodo       = '$periodo'
          and Planificacion.NivelEstudios = '$nivel'
          and Planificacion.Capacidad     > 0
          and Planificacion.Ano           = PlanificacionHorarios.Ano
          and Planificacion.Periodo       = PlanificacionHorarios.Periodo
          and Planificacion.NivelEstudios = PlanificacionHorarios.NivelEstudios
          and Planificacion.Asignatura    = PlanificacionHorarios.Asignatura
          and Planificacion.Secuencia     = PlanificacionHorarios.Secuencia
          and Planificacion.Turno         = PlanificacionHorarios.Turno
          and Planificacion.Seccion       = PlanificacionHorarios.Seccion
          and PlanificacionHorarios.Aula  <> 'PV'
          and PlanificacionHorarios.Dia   = 5
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Viernes,
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Planificacion.Asignatura||'/'||Planificacion.Turno||'-'||Planificacion.Seccion||'/'||PlanificacionHorarios.Aula) as Materia
         From Unimar.Profesores, Unimar.Planificacion, Unimar.PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$ano'
          and Planificacion.Periodo       = '$periodo'
          and Planificacion.NivelEstudios = '$nivel'
          and Planificacion.Capacidad     > 0
          and Planificacion.Ano           = PlanificacionHorarios.Ano
          and Planificacion.Periodo       = PlanificacionHorarios.Periodo
          and Planificacion.NivelEstudios = PlanificacionHorarios.NivelEstudios
          and Planificacion.Asignatura    = PlanificacionHorarios.Asignatura
          and Planificacion.Secuencia     = PlanificacionHorarios.Secuencia
          and Planificacion.Turno         = PlanificacionHorarios.Turno
          and Planificacion.Seccion       = PlanificacionHorarios.Seccion
          and PlanificacionHorarios.Aula  <> 'PV'
          and PlanificacionHorarios.Dia   = 6
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Sabado,
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Planificacion.Asignatura||'/'||Planificacion.Turno||'-'||Planificacion.Seccion||'/'||PlanificacionHorarios.Aula) as Materia
         From Unimar.Profesores, Unimar.Planificacion, Unimar.PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$ano'
          and Planificacion.Periodo       = '$periodo'
          and Planificacion.NivelEstudios = '$nivel'
          and Planificacion.Capacidad     > 0
          and Planificacion.Ano           = PlanificacionHorarios.Ano
          and Planificacion.Periodo       = PlanificacionHorarios.Periodo
          and Planificacion.NivelEstudios = PlanificacionHorarios.NivelEstudios
          and Planificacion.Asignatura    = PlanificacionHorarios.Asignatura
          and Planificacion.Secuencia     = PlanificacionHorarios.Secuencia
          and Planificacion.Turno         = PlanificacionHorarios.Turno
          and Planificacion.Seccion       = PlanificacionHorarios.Seccion
          and PlanificacionHorarios.Aula  <> 'PV'
          and PlanificacionHorarios.Dia   = 7
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Domingo
 Where Horas.Hora = Lunes.Hora (+)
   and Horas.Hora = Martes.Hora (+)
   and Horas.Hora = Miercoles.Hora (+)
   and Horas.Hora = Jueves.Hora (+)
   and Horas.Hora = Viernes.Hora (+)
   and Horas.Hora = Sabado.Hora (+)
   and Horas.Hora = Domingo.Hora (+)
 Order By Horas.Hora";

	$horarios->crear_registro($SQL,$conecta->conn,$conecta->database_conn);


	//cargar QUERY de carga academica
	$SQL = "Select Profesores.Nacionalidad,Profesores.Cedula, Profesores.Apellidos, Profesores.Nombres,
       Planificacion.Asignatura, Asignaturas.Descripcion,
       Planificacion.Turno||'-'||Planificacion.Seccion as Seccion, decode(HorasAsignadas.Horas,Null,0,HorasAsignadas.Horas) as Horas
  From Unimar.Profesores, Unimar.Planificacion, Unimar.Asignaturas,
      (Select PlanificacionHorarios.Ano, PlanificacionHorarios.Periodo, PlanificacionHorarios.NivelEstudios,
              PlanificacionHorarios.Asignatura, PlanificacionHorarios.Secuencia, PlanificacionHorarios.Turno,
              PlanificacionHorarios.Seccion, Count(*) as Horas
         From Unimar.PlanificacionHorarios
        Where PlanificacionHorarios.Aula <> 'PV'
        Group by PlanificacionHorarios.Ano, PlanificacionHorarios.Periodo, PlanificacionHorarios.NivelEstudios,
                 PlanificacionHorarios.Asignatura, PlanificacionHorarios.Secuencia, PlanificacionHorarios.Turno,
                 PlanificacionHorarios.Seccion) HorasAsignadas
 Where Profesores.Cedula           = $cedula
   and Planificacion.Profesor      = Profesores.Cedula
   and Planificacion.Ano           = '$ano'
   and Planificacion.Periodo       = '$periodo'
   and Planificacion.NivelEstudios = '$nivel'
   and Planificacion.Capacidad     > 0
   and Planificacion.Asignatura    = Asignaturas.Asignatura
   and Planificacion.Secuencia     = Asignaturas.Secuencia
   and Planificacion.Ano           = HorasAsignadas.Ano (+)
   and Planificacion.Periodo       = HorasAsignadas.Periodo (+)
   and Planificacion.NivelEstudios = HorasAsignadas.NivelEstudios (+)
   and Planificacion.Asignatura    = HorasAsignadas.Asignatura (+)
   and Planificacion.Secuencia     = HorasAsignadas.Secuencia (+)
   and Planificacion.Turno         = HorasAsignadas.Turno (+)
   and Planificacion.Seccion       = HorasAsignadas.Seccion (+)";

	$materias->crear_registro($SQL,$conecta->conn,$conecta->database_conn);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Carga Académica de un Profesor</title>

<style type="text/css">
<!--
.Tabla {
	background-color: #FFFFFF;
	border-collapse:	collapse;
	border: 1px solid #000000;
}

.Celda2 {
	background-color:#FFFFFF;
	text-align: center;
	border-collapse:collapse;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9px;
	color: #000000;
	padding: 2px;
	border: 1px solid #000000;
}

.CeldaTitulo {
	background-color:#FFFFFF;
	text-align: center;
	border-collapse:collapse;
	font-family: Arial, Helvetica, sans-serif;
	font-weight:bold;
	font-size:11px;
	color: #000000;
	padding: 2px;
	border: 1px solid #000000;	
}

body {
	margin-left: 1px;
	margin-top: 1px;
	margin-right: 1px;
	margin-bottom: 1px;
}
.cal_vacio{
	padding:0px;
	line-height:15px;
	height:15px;
	text-align:none;
	FONT-SIZE: 11px; COLOR: #000000; FONT-FAMILY: Arial, Helvetica, sans-serif; TEXT-DECORATION: none;
	/*border: 1px solid #CCCCCC;*/
}
.Estilo7 {font-family: Arial, Helvetica, sans-serif; font-size: 11px; }
.Estilo9 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }
.boton {
	background-color: #E6EAEE;
	border: 1px solid #666666;
	width:110px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
	text-decoration: none;
	cursor:pointer;
}
-->
</style>
</head>

<body onLoad="">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="2%">&nbsp;</td>
    <td width="96%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="Estilo7" align="center" valign="middle"><strong><u><span style="cursor:pointer" onClick="javascript:window.print();">Carga Acad&eacute;mica</span></u></strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="Estilo7" align="center" valign="middle">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="Estilo7" align="center" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="9%"><span class="Estilo9">C&eacute;dula:</span></td>
        <td width="91%" class="Estilo7"><?php echo $cedula;?>&nbsp;</td>
      </tr>
      <tr>
        <td><span class="Estilo9">Apellidos:</span></td>
        <td class="Estilo7"><?php echo $materias->valor_campo("APELLIDOS");?>&nbsp;</td>
      </tr>
      <tr>
        <td><span class="Estilo9">Nombres:</span></td>
        <td class="Estilo7"><?php echo $materias->valor_campo("NOMBRES");?>&nbsp;</td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="Estilo7" align="right" valign="middle">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="Estilo7" align="center" valign="middle"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="Tabla">
      <tr>
        <td width="18%" class="CeldaTitulo">ASIGNATURA</td>
        <td width="50%" class="CeldaTitulo">DESCRIPCI&Oacute;N</td>
        <td width="15%" class="CeldaTitulo">SECCI&Oacute;N</td>
        <td width="17%" class="CeldaTitulo">HORAS</td>
      </tr>
	  <?php do{?>
      <tr>	  
        <td class="Celda2"><?php echo $materias->valor_campo("ASIGNATURA");?></td>
        <td class="Celda2"><?php echo $materias->valor_campo("DESCRIPCION");?></td>
        <td class="Celda2"><?php echo $materias->valor_campo("SECCION");?></td>
        <td class="Celda2"><?php echo $materias->valor_campo("HORAS");?></td>
      </tr>
	  <?php }while ($materias->Proximoregistro());?>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="Estilo7" align="center" valign="middle">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="Estilo7" align="center" valign="middle"><table class="Tabla" width="100%" border="0" cellpadding="0" cellspacing="0">     
      <tr>
        <td height="24" align="center" valign="middle" class="CeldaTitulo">HORA</td>
        <td align="center" valign="middle" class="CeldaTitulo">LUNES</td>
        <td align="center" valign="middle" class="CeldaTitulo">MARTES</td>
        <td align="center" valign="middle" class="CeldaTitulo">MI&Eacute;RCOLES</td>
        <td align="center" valign="middle" class="CeldaTitulo">JUEVES</td>
        <td align="center" valign="middle" class="CeldaTitulo">VIERNES</td>
        <td align="center" valign="middle" class="CeldaTitulo">S&Aacute;BADO</td>
        <td align="center" valign="middle" class="CeldaTitulo">DOMINGO</td>
      </tr>
	   <?php do{ ?>
      <tr class="Estilo7">
        <td width="13%" height="24" align="center" valign="middle" class="Celda2"><?php echo $horarios->valor_campo("DESCRIPCION");?>&nbsp;</td>
        <td width="13%" align="center" valign="middle" class="Celda2"><?php echo $horarios->valor_campo("LUN");?>&nbsp;</td>
        <td width="13%" align="center" valign="middle" class="Celda2"><?php echo $horarios->valor_campo("MAR");?>&nbsp;</td>
        <td width="12%" align="center" valign="middle" class="Celda2"><?php echo $horarios->valor_campo("MIE");?>&nbsp;</td>
        <td width="13%" align="center" valign="middle" class="Celda2"><?php echo $horarios->valor_campo("JUE");?>&nbsp;</td>
        <td width="13%" align="center" valign="middle" class="Celda2"><?php echo $horarios->valor_campo("VIE");?>&nbsp;</td>
        <td width="14%" align="center" valign="middle" class="Celda2"><?php echo $horarios->valor_campo("SAB");?>&nbsp;</td>
        <td width="9%" align="center" valign="middle" class="Celda2"><?php echo $horarios->valor_campo("DOM");?>&nbsp;</td>
      </tr>
       <?php }while ($horarios->Proximoregistro());?>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
	$materias->liberar();
	$horarios->liberar();
	$conecta->cerrar();
?>