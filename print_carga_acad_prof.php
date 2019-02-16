<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";
date_default_timezone_set('America/Caracas');

/*evitar sql injection para valores enteros*/
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


// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "acceso.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php require_once('unimarconn2.php'); ?>
<?php
	
	mysql_select_db($database_conn, $conexion);
	
	
	$cedula = GetSQLValueString($_GET['cedula'],"int");
		$var_periodo = $_GET['Periodo'];
		$var_ano = $_GET['Ano'];
		$var_nivel = $_GET['NivelEstudios'];

	//cargar QUERY de HORARIOS
	$SQL = "Select  Horas.Hora, Horas.Descripcion, 
        Lunes.Materia As Lun, 
        Martes.Materia As Mar,
        Miercoles.Materia as Mie, 
        Jueves.Materia Jue,
        Viernes.Materia Vie, 
        Sabado.Materia as Sab,
        Domingo.Materia as Dom
  From Horas
  Left Join
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Concat(Planificacion.Asignatura,'/',Planificacion.Turno,'-',Planificacion.Seccion,'/',PlanificacionHorarios.Aula)) as Materia
         From Profesores, Planificacion, PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$var_ano'
          and Planificacion.Periodo       = '$var_periodo'
          and Planificacion.NivelEstudios = '$var_nivel'
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
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Lunes
    On Horas.Hora = Lunes.Hora 
  Left Join
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Concat(Planificacion.Asignatura,'/',Planificacion.Turno,'-',Planificacion.Seccion,'/',PlanificacionHorarios.Aula)) as Materia
         From Profesores, Planificacion, PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$var_ano'
          and Planificacion.Periodo       = '$var_periodo'
          and Planificacion.NivelEstudios = '$var_nivel'
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
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Martes
    On Horas.Hora = Martes.Hora 
  Left Join
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Concat(Planificacion.Asignatura,'/',Planificacion.Turno,'-',Planificacion.Seccion,'/',PlanificacionHorarios.Aula)) as Materia
         From Profesores, Planificacion, PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$var_ano'
          and Planificacion.Periodo       = '$var_periodo'
          and Planificacion.NivelEstudios = '$var_nivel'
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
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Miercoles
    On Horas.Hora = Miercoles.Hora 
  Left Join
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Concat(Planificacion.Asignatura,'/',Planificacion.Turno,'-',Planificacion.Seccion,'/',PlanificacionHorarios.Aula)) as Materia
         From Profesores, Planificacion, PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$var_ano'
          and Planificacion.Periodo       = '$var_periodo'
          and Planificacion.NivelEstudios = '$var_nivel'
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
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Jueves
    On Horas.Hora = Jueves.Hora 
  Left Join
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Concat(Planificacion.Asignatura,'/',Planificacion.Turno,'-',Planificacion.Seccion,'/',PlanificacionHorarios.Aula)) as Materia
         From Profesores, Planificacion, PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$var_ano'
          and Planificacion.Periodo       = '$var_periodo'
          and Planificacion.NivelEstudios = '$var_nivel'
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
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Viernes
    On Horas.Hora = Viernes.Hora
  Left Join
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Concat(Planificacion.Asignatura,'/',Planificacion.Turno,'-',Planificacion.Seccion,'/',PlanificacionHorarios.Aula)) as Materia
         From Profesores, Planificacion, PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$var_ano'
          and Planificacion.Periodo       = '$var_periodo'
          and Planificacion.NivelEstudios = '$var_nivel'
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
        Group by  PlanificacionHorarios.Dia, PlanificacionHorarios.Hora) Sabado
    On Horas.Hora = Sabado.Hora 
  Left Join
      (Select PlanificacionHorarios.Dia, PlanificacionHorarios.Hora, Count(*) as Choque,
              Min(Concat(Planificacion.Asignatura,'/',Planificacion.Turno,'-',Planificacion.Seccion,'/',PlanificacionHorarios.Aula)) as Materia
         From Profesores, Planificacion, PlanificacionHorarios
        Where Profesores.Cedula           = $cedula
          and Planificacion.Profesor      = Profesores.Cedula
          and Planificacion.Ano           = '$var_ano'
          and Planificacion.Periodo       = '$var_periodo'
          and Planificacion.NivelEstudios = '$var_nivel'
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
    On Horas.Hora = Domingo.Hora 
 Order By Horas.Hora";


	$query_Recordset1 = $SQL;
	$Recordset1 = mysql_query($query_Recordset1, $conexion) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);


	
	
	//mysql_select_db($database_conn, $conexion);


	//cargar QUERY de carga academica
	$SQL = "Select Profesores.Cedula, Profesores.Apellidos, Profesores.Nombres,
       Planificacion.Asignatura, Asignaturas.Descripcion,
       Concat(Planificacion.Turno,'-',Planificacion.Seccion) as Seccion, If(IsNull(HorasAsignadas.Horas),0,HorasAsignadas.Horas) as Horas
  From Planificacion
 Inner Join 
       Profesores
    On Planificacion.Profesor      = Profesores.Cedula
 Inner Join
       Asignaturas
    On Planificacion.Asignatura    = Asignaturas.Asignatura
   and Planificacion.Secuencia     = Asignaturas.Secuencia
  left Join
      (Select PlanificacionHorarios.Ano, PlanificacionHorarios.Periodo, PlanificacionHorarios.NivelEstudios,
              PlanificacionHorarios.Asignatura, PlanificacionHorarios.Secuencia, PlanificacionHorarios.Turno,
              PlanificacionHorarios.Seccion, Count(*) as Horas
         From PlanificacionHorarios
        Where PlanificacionHorarios.Aula <> 'PV'
        Group by PlanificacionHorarios.Ano, PlanificacionHorarios.Periodo, PlanificacionHorarios.NivelEstudios,
                 PlanificacionHorarios.Asignatura, PlanificacionHorarios.Secuencia, PlanificacionHorarios.Turno,
                 PlanificacionHorarios.Seccion) HorasAsignadas
    On Planificacion.Ano           = HorasAsignadas.Ano 
   and Planificacion.Periodo       = HorasAsignadas.Periodo 
   and Planificacion.NivelEstudios = HorasAsignadas.NivelEstudios 
   and Planificacion.Asignatura    = HorasAsignadas.Asignatura 
   and Planificacion.Secuencia     = HorasAsignadas.Secuencia 
   and Planificacion.Turno         = HorasAsignadas.Turno 
   and Planificacion.Seccion       = HorasAsignadas.Seccion 
 Where Planificacion.Profesor      = $cedula
   and Planificacion.Ano           = '$var_ano'
   and Planificacion.Periodo       = '$var_periodo'
   and Planificacion.NivelEstudios = '$var_nivel'
   and Planificacion.Capacidad     > 0";

	mysql_select_db($database_conn, $conexion);
    $query_Recordset2 = $SQL;
	$Recordset2 = mysql_query($query_Recordset2, $conexion) or die(mysql_error());
	$row_Recordset2 = mysql_fetch_assoc($Recordset2);
	$totalRows_Recordset2 = mysql_num_rows($Recordset2);
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

<body onLoad="javascript:window.print();">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="2%">&nbsp;</td>
    <td width="96%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="Estilo7" align="center" valign="middle"><strong><u><span style="cursor:pointer">Carga Acad&eacute;mica</span></u></strong></td>
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
        <td class="Estilo7"><?php echo $row_Recordset2['Apellidos'];?>&nbsp;</td>
      </tr>
      <tr>
        <td><span class="Estilo9">Nombres:</span></td>
        <td class="Estilo7"><?php echo $row_Recordset2['Nombres'];?>&nbsp;</td>
      </tr>
      <tr>
        <td><span class="Estilo9">Impreso:</span></td>
        <td class="Estilo7"><?php echo date("d-m-Y - g:i a");?>&nbsp;</td>
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
        <td class="Celda2"><?php echo $row_Recordset2['Asignatura'];?></td>
        <td class="Celda2"><?php echo $row_Recordset2['Descripcion'];?></td>
        <td class="Celda2"><?php echo $row_Recordset2['Seccion'];?></td>
        <td class="Celda2"><?php echo $row_Recordset2['Horas'];?></td>
      </tr>
	  <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));?>
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
        <td width="13%" height="24" align="center" valign="middle" class="Celda2"><?php echo $row_Recordset1['Descripcion'];?>&nbsp;</td>
        <td width="13%" align="center" valign="middle" class="Celda2"><?php echo $row_Recordset1['Lun'];?>&nbsp;</td>
        <td width="13%" align="center" valign="middle" class="Celda2"><?php echo $row_Recordset1['Mar'];?>&nbsp;</td>
        <td width="12%" align="center" valign="middle" class="Celda2"><?php echo $row_Recordset1['Mie'];?>&nbsp;</td>
        <td width="13%" align="center" valign="middle" class="Celda2"><?php echo $row_Recordset1['Jue'];?>&nbsp;</td>
        <td width="13%" align="center" valign="middle" class="Celda2"><?php echo $row_Recordset1['Vie'];?>&nbsp;</td>
        <td width="14%" align="center" valign="middle" class="Celda2"><?php echo $row_Recordset1['Sab'];?>&nbsp;</td>
        <td width="9%" align="center" valign="middle" class="Celda2"><?php echo $row_Recordset1['Dom'];?>&nbsp;</td>
      </tr>
       <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));?>
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
	mysql_free_result($Recordset1);
	mysql_free_result($Recordset2);
?>