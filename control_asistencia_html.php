<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";
date_default_timezone_set('America/Caracas');
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
	session_start();
	//parametros
		$var_periodo = $_GET['Periodo'];
		$var_ano = $_GET['Ano'];
		$var_nivel = $_GET['NivelEstudios'];
	
	/*evitar sql injection en variable tipo cadena*/
	$datos = isset($_GET['asig']) ? mysql_real_escape_string($_GET['asig']) : '';
	$sec = isset($_GET['sec']) ? mysql_real_escape_string($_GET['sec']) : '';
	
	list($asignatura, $descrip, $secuencia) = split('//', $datos);
	list($turno, $seccion) = split('-', $sec);
	$cedula = $_SESSION['cedula'];
	
	$consulta = "Select EstudiantesHistorico.Cedula, Estudiantes.Apellidos, Estudiantes.Nombres
  				From EstudiantesHistorico, Estudiantes
			Where EstudiantesHistorico.Ano = '$var_ano'
			   and EstudiantesHistorico.Periodo = '$var_periodo'
			   and EstudiantesHistorico.NivelEstudios = '$var_nivel'
			   and EstudiantesHistorico.Turno = '$turno'
			   and EstudiantesHistorico.Seccion = '$seccion'
			   and EstudiantesHistorico.Asignatura = '$asignatura'
			   and EstudiantesHistorico.Secuencia = '$secuencia'
			   and EstudiantesHistorico.Cedula = Estudiantes.Cedula
			Order By Estudiantes.Apellidos, Estudiantes.Nombres";
	mysql_select_db($database_conn, $conexion);
	$query_Recordset1 = $consulta;
	$Recordset1 = mysql_query($query_Recordset1, $conexion) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	
	/*query horarios seccion*/
	$consulta = "SELECT
					PlanificacionHorarios.Aula,
					Semana.Descripcion,
					PlanificacionHorarios.Turno,
					Horas.Desde,
					Horas.Hasta,
					Planificacion.Seccion,
					Planificacion.Asignatura
				FROM
			Planificacion
Inner Join PlanificacionHorarios ON PlanificacionHorarios.Ano = Planificacion.Ano AND PlanificacionHorarios.Periodo = Planificacion.Periodo AND PlanificacionHorarios.NivelEstudios = Planificacion.NivelEstudios AND PlanificacionHorarios.Asignatura = Planificacion.Asignatura AND PlanificacionHorarios.Secuencia = Planificacion.Secuencia AND PlanificacionHorarios.Turno = Planificacion.Turno AND PlanificacionHorarios.Seccion = Planificacion.Seccion
Inner Join Profesores ON Planificacion.Profesor = Profesores.Cedula
Inner Join Semana ON PlanificacionHorarios.Dia = Semana.Dia
Inner Join Horas ON Horas.Turno = Planificacion.Turno AND Horas.Hora = PlanificacionHorarios.Hora
			where
				Profesores.Cedula = $cedula
				and Planificacion.Capacidad     > 0
				and PlanificacionHorarios.Aula  <> 'PV'
				and Planificacion.Ano           = '$var_ano'
				and Planificacion.Periodo       = '$var_periodo'
				and Planificacion.NivelEstudios = '$var_nivel'
				and Planificacion.Turno         = '$turno'
				and Planificacion.Seccion       = '$seccion'
				and Planificacion.Asignatura       = '$asignatura'";
	
	mysql_select_db($database_conn, $conexion);
	$query_Recordset2 = $consulta;
	$Recordset2 = mysql_query($query_Recordset2, $conexion) or die(mysql_error());
	$row_Recordset2 = mysql_fetch_assoc($Recordset2);
	$totalRows_Recordset2 = mysql_num_rows($Recordset2);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Planilla de Control de Asistencia</title>
<style type="text/css">
<!--
.textopequeno {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9px; }
.textoExtraGrandeTitulo {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-weight:bold;}
.textoExtraGrande {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px;}
.textogrande {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.textoOculto {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color:#FFFFFF;}
.textograndeTitulo {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; font-weight:bold;}
p{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
	padding: 0px;
	margin: 0px;
}
.Tabla {
	background-color: #FFFFFF;
	border-collapse:	collapse;
	border: 1px solid #000000;
}
.Celda3 {
	background-color: #FFFFFF;
	/*text-align: left;*/
	/*font-family: Arial, Helvetica, sans-serif;*/
	/*font-size: 11px;*/
	color: #000000;
	/*padding: 3px;*/
	border: 1px solid #000000;
}
.bordeDerecho{
		border-left: 1px solid #000;
}
.bordeIzquierdo{
		border-right: 1px solid #000;
}
.bordeArriba{
		border-top: 1px solid #000;
}
.bordeAbajo{
		border-bottom: 1px solid #000;
}
-->
</style>
</head>

<body onLoad="javascript:window.print();">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td align="center" valign="middle"><p class="textoExtraGrandeTitulo">UNIVERSIDAD DE MARGARITA</p>
    <p class="textoExtraGrandeTitulo">VICERRECTORADO ACAD&Eacute;MICO</p>
    <p class="textoExtraGrande">Planilla de control de Asistencia</p>    </td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td width="49%" align="left" valign="top"><p><span class="textograndeTitulo">ASIGNATURA:</span> <?php echo $asignatura;?> - <?php echo $descrip;?></p>
    <p><span class="textograndeTitulo">SECCI&Oacute;N:</span> <?php echo $turno;?>-<?php echo $seccion;?></p>
    <p><span class="textograndeTitulo">PROFESOR:</span> <?php echo $_SESSION['cedula'];?> - <?php echo $_SESSION['nombre']." ".$_SESSION['apellido']?></p>
    <p><span class="textograndeTitulo">IMPRESO:</span> <?php echo date("d-m-Y - g:i a");?></p>
    <p>&nbsp;</p>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="Tabla">
      <tr class="Celda3">
        <td height="19" class="textogrande bordeAbajo">Observaciones:</td>
      </tr>
      <tr>
        <td class="textogrande bordeAbajo">&nbsp;</td>
      </tr>
      <tr>
        <td class="textogrande bordeAbajo">&nbsp;</td>
      </tr>
      <tr>
        <td class="textogrande bordeAbajo">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>    
    <p>&nbsp;</p></td>
    <td width="51%" align="right" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="39%" align="center" valign="middle" class="textograndeTitulo">Horario</td>
        <td width="25%" align="left" valign="middle" class="textograndeTitulo">DIA</td>
        <td width="11%" align="left" valign="middle" class="textograndeTitulo">AULA</td>
        <td width="13%" align="left" valign="middle" class="textograndeTitulo">Desde</td>
        <td width="12%" align="left" valign="middle" class="textograndeTitulo">Hasta</td>
      </tr>
      <?php do{ ?>
      <tr>
        <td align="center" valign="middle" class="textogrande">&nbsp;</td>
        <td align="left" valign="middle" class="textogrande"><?php echo $row_Recordset2['Descripcion'];?></td>
        <td align="left" valign="middle" class="textogrande"><?php echo $row_Recordset2['Aula'];?></td>
        <td align="left" valign="middle" class="textogrande"><?php echo $row_Recordset2['Desde'];?></td>
        <td align="left" valign="middle" class="textogrande"><?php echo $row_Recordset2['Hasta'];?></td>
      </tr>
      <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));?>
    </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <!--DWLayoutTable-->
  <tr>
    <td height="21">&nbsp;</td>
    <td align="left" valign="middle" class="textograndeTitulo">&nbsp;</td>
    <td align="left" valign="middle" class="textograndeTitulo">&nbsp;</td>
    <td align="left" valign="middle" class="textograndeTitulo">&nbsp;</td>
    <td colspan="12" align="center" valign="middle" class="textograndeTitulo bordeArriba bordeDerecho bordeIzquierdo">DIAS</td>
    <td align="center" valign="middle" class="textograndeTitulo">&nbsp;</td>
  </tr>
  <tr>
    <td width="3%" class="bordeArriba bordeAbajo bordeDerecho bordeIzquierdo">&nbsp;</td>
    <td width="8%" align="center" valign="middle" class="textograndeTitulo bordeArriba bordeAbajo bordeIzquierdo">CÉDULA</td>
    <td width="25%" align="center" valign="middle" class="textograndeTitulo bordeArriba bordeAbajo bordeIzquierdo">APELLIDOS</td>
    <td width="25%" align="center" valign="middle" class="textograndeTitulo bordeArriba bordeAbajo bordeIzquierdo">NOMBRES</td>
    <td width="25" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="25" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="25" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="25" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="25" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="25" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="26" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="26" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="26" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="26" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="26" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="26" class="bordeArriba bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td width="3%" align="center" valign="middle" class="textograndeTitulo bordeArriba bordeAbajo bordeIzquierdo">TI</td>
  </tr>
  <?php $cont=0; do{ $cont++;?>
  <tr>
    <td align="center" valign="middle" class="textogrande bordeDerecho  bordeAbajo bordeIzquierdo"><?php echo $cont;?></td>
    <td class="bordeAbajo bordeIzquierdo textogrande"><?php echo $row_Recordset1['Cedula'];?></td>
    <td class="bordeAbajo bordeIzquierdo textogrande"><?php echo $row_Recordset1['Apellidos'];?></td>
    <td class="bordeAbajo bordeIzquierdo textogrande"><?php echo $row_Recordset1['Nombres'];?></td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
    <td class="bordeAbajo bordeIzquierdo">&nbsp;</td>
  </tr>
  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td width="50%" align="center" valign="middle" class="textograndeTitulo">Firma del Profesor</td>
    <td width="50%" align="center" valign="middle" class="textograndeTitulo">Vto. Bno. del Decano</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
	mysql_free_result($Recordset1);
	mysql_free_result($Recordset2);
?>