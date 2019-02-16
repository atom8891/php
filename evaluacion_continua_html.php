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
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Planilla Para Evaluaci&oacute;n Cont&iacute;nua</title>

<style type="text/css">
<!--
.textopequeno {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9px; }
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
.bordeArriba{
		border-top: 1px solid #000;
}
.bordeAbajo{
		border-top: 1px solid #000;
}
-->
</style>
</head>

<body onLoad="javascript:window.print();">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="47%" align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="textopequeno">UNIMAR</td>
      </tr>
      <tr>
        <td class="textopequeno">PLANILLA PARA EVALUACI&Oacute;N CONT&Iacute;NUA</td>
      </tr>
      <tr>
        <td class="textopequeno">MATERIA: <?php echo $asignatura;?> - <?php echo $descrip;?></td>
      </tr>
      <tr>
        <td class="textopequeno">PROFESOR: (<?php echo $_SESSION['cedula'];?>) <?php echo $_SESSION['apellido']." ".$_SESSION['nombre']?></td>
      </tr>
      <tr>
      	<td class="textopequeno">IMPRESO:</span> <?php echo date("d-m-Y - g:i a");?></td>
      </tr>
    </table></td>
    <td width="53%" align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="textopequeno"><div align="right">&nbsp;</div></td>
      </tr>
      <tr>
        <td class="textopequeno"><div align="right">LAPSO ACAD&Eacute;MICO: <?php echo $var_ano.'-'.$var_periodo; ?> </div></td>
      </tr>
      <tr>
        <td class="textopequeno"><div align="right">TURNO: <?php echo $turno;?></div></td>
      </tr>
      <tr>
        <td class="textopequeno"><div align="right">SECCI&Oacute;N: <?php echo $seccion;?></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="47%" height="26" align="center" valign="middle" class="textograndeTitulo">&nbsp;</td>
    <td width="17%" class="textogrande">CORTE ________</td>
    <td width="16%" class="textogrande">CORTE ________</td>
    <td width="16%" class="textogrande">CORTE ________</td>
    <td width="4%" align="center" valign="top" class="textopequeno"></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="Tabla">
  <tr class="Celda3">
    <td width="47%" align="center" valign="middle" class="textograndeTitulo">ALUMNOS</td>
    <td width="17%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <!--DWLayoutTable-->
      <tr>
        <td height="12" colspan="4" valign="top" class="bordeDerecho textopequeno"><div align="center">EVALUACI&Oacute;N</div></td>
        <td width="44" rowspan="2" align="center" valign="top" class="bordeDerecho textopequeno">NL</td>
      </tr>
      <tr>
        <td width="46" height="19" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="40" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="44" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="43" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
      </tr>
    </table></td>
    <td width="16%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <!--DWLayoutTable-->
      <tr>
        <td height="12" colspan="4" valign="top" class="bordeDerecho textopequeno"><div align="center">EVALUACI&Oacute;N</div></td>
        <td width="44" rowspan="2" align="center" valign="top" class="bordeDerecho textopequeno">NL</td>
      </tr>
      <tr>
        <td width="46" height="19" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="40" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="44" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="43" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
      </tr>
    </table></td>
    <td width="16%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <!--DWLayoutTable-->
      <tr>
        <td height="12" colspan="4" valign="top" class="bordeDerecho textopequeno"><div align="center">EVALUACI&Oacute;N</div></td>
        <td width="44" rowspan="2" align="center" valign="top" class="bordeDerecho textopequeno">NL</td>
      </tr>
      <tr>
        <td width="46" height="19" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="40" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="44" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="43" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
      </tr>
    </table></td>
    <td width="4%" align="center" valign="top" class="bordeDerecho textopequeno">DE</td>
  </tr>
  
  <?php do{ ?>
  <tr class="Celda3">
    <td align="left" valign="middle" class="textograndeTitulo bordeArriba"><table width="100%" height="31" border="0" cellpadding="5" cellspacing="0">
      <tr class="textopequeno">
        <td width="17%" align="left" valign="middle"><?php echo $row_Recordset1['Cedula'];?></td>
        <td width="83%" align="left" valign="middle" class="bordeDerecho"><?php echo $row_Recordset1['Apellidos']." ".$row_Recordset1['Nombres']?></td>
      </tr>
    </table></td>
    <td class="bordeArriba"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <!--DWLayoutTable-->
      <tr>
        <td height="12" colspan="5" valign="top" class=" bordeDerecho textoOculto">.</td>
      </tr>
      <tr>
        <td width="46" height="19" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="40" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="44" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="43" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="44" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
      </tr>
    </table></td>
    <td class="bordeArriba"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <!--DWLayoutTable-->
      <tr>
        <td height="12" colspan="5" valign="top" class=" bordeDerecho textoOculto">.</td>
      </tr>
      <tr>
        <td width="46" height="19" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="40" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="44" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="43" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="44" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
      </tr>
    </table></td>
    <td class="bordeArriba"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <!--DWLayoutTable-->
      <tr>
        <td height="12" colspan="5" valign="top" class=" bordeDerecho textoOculto">.</td>
      </tr>
      <tr>
        <td width="46" height="19" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="40" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="44" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="43" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <td width="44" valign="top" class="bordeDerecho"><!--DWLayoutEmptyCell-->&nbsp;</td>
      </tr>
    </table></td>
    <td class="bordeDerecho bordeArriba">&nbsp;</td>
  </tr>
  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));?>
</table>
<p>&nbsp;</p>
<p><span class="textograndeTitulo">NOTA:</span> Este formulario no debe llevar tachadura ni enmienda</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="41%" align="center" valign="middle"><p>____________________________________________________</p>
      <p align="center">EL PROFESOR</p>
    </td>
    <td width="15%">&nbsp;</td>
    <td width="44%" align="center" valign="middle"><p>_____________________________________________________</p>
      <p align="center">JEFE DE CONTROL DE ESTUDIOS</p>
    <p></p></td>
  </tr>
</table>
</body>
</html>
