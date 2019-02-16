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
<?php require('fpdf16/fpdf.php');?>
<?php	
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
	$nombre = $_SESSION['nombre'];
	$apellido = $_SESSION['apellido'];  	
	
/*	$consulta = "Select EstudiantesHistorico.Cedula, Estudiantes.Apellidos, Estudiantes.Nombres
  				From EstudiantesHistorico, Estudiantes
			Where EstudiantesHistorico.Ano = '$var_ano'
			   and EstudiantesHistorico.Periodo = '$var_periodo'
			   and EstudiantesHistorico.NivelEstudios = '$var_nivel'
			   and EstudiantesHistorico.Turno = '$turno'
			   and EstudiantesHistorico.Seccion = '$seccion'
			   and EstudiantesHistorico.Asignatura = '$asignatura'
			   and EstudiantesHistorico.Secuencia = '$secuencia'
			   and EstudiantesHistorico.Cedula = Estudiantes.Cedula
			Order By Estudiantes.Apellidos, Estudiantes.Nombres";*/

	$consulta = "Select EstudiantesHistorico.Cedula, Estudiantes.Apellidos, Estudiantes.Nombres
  				From EstudiantesHistorico, Estudiantes
			Where EstudiantesHistorico.Ano = '$var_ano'
			   and EstudiantesHistorico.Periodo = '$var_periodo'
			   and EstudiantesHistorico.NivelEstudios = '$var_nivel'
			   and EstudiantesHistorico.Turno = '$turno'
			   and EstudiantesHistorico.Seccion = '$seccion'
			   and EstudiantesHistorico.Asignatura = '$asignatura'
			   and EstudiantesHistorico.Secuencia = '0'
			   and EstudiantesHistorico.Cedula = Estudiantes.Cedula
			Order By Estudiantes.Apellidos, Estudiantes.Nombres";
			
	
	mysql_select_db($database_conn, $conexion);
	$query_Recordset1 = $consulta;
	$Recordset1 = mysql_query($query_Recordset1, $conexion) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	
	/*CREACION DEL REPORTE PDF*/
	//$pdf= new FPDF('P', 'mm', array(140,215));
	//$pdf= new FPDF();
	//$formato = array(215,140);
	$pdf = $pdf= new FPDF();
	$pdf->Open();
	
	$pdf->SetLeftMargin(8);
	$pdf->SetAutoPageBreak(true,30);
	$pdf->SetTopMargin(8);
	$pdf->SetFont('Arial','',11);
	$pdf->SetTitle('Planilla para evaluación contínua');
	$pdf->AddPage();
	
	/*colocar informacion de encabezado*/	
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(100,10,'UNIMAR');
	$pdf->SetX(188);
	$pdf->Cell(100,10,'Pág. '.$pdf->PageNo());
	$pdf->Ln(5);
	$pdf->Cell(100,10,'PLANILLA PARA EVALUACIÓN CONTÍNUA ');
	$pdf->SetX(128);
	$pdf->Cell(100,10,'                              LAPSO ACADÉMICO: '.$var_ano.'-'.$var_periodo);
	$pdf->Ln(5);
	$pdf->Cell(100,10,'MATERIA: '.$asignatura .' - '.$descrip);
	$pdf->SetX(184);
	$pdf->Cell(100,10,'Turno:  '.$turno);
	$pdf->Ln(5);
	//$pdf->Cell(100,10,'PROFESOR: ('.$cedula.')  '. $nombre);
	$pdf->Cell(100,10,'PROFESOR: ('.$cedula.')  '.$apellido.', '.$nombre);
	$pdf->SetX(180);
	$pdf->Cell(100,10,'Sección:  '.$seccion);
	$pdf->Ln(5);
	$pdf->Cell(100,10,'IMPRESO: '.date ("d-m-Y - g:i a"),0,0);
	

	//ubicar la palabra CORTE____
	$pdf->Ln(13);
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(100);	
	$pdf->Cell(100,10,'CORTE ________');
	$pdf->SetX(130);	
	$pdf->Cell(100,10,'CORTE ________');
	$pdf->SetX(160);
	$pdf->Cell(100,10,'CORTE ________');
		
	$pdf->Ln(7);
	
	
	/*CREACION DE LA TABLA DE ALUMNOS*/
	//primer encabezado--------------
	$pdf->SetFont('Arial','',12);
	//lineas de separacion****
	$pdf->Line(106,48,106,50);
	$pdf->Line(112,48,112,50);
	$pdf->Line(118,48,118,50);
	
	$pdf->Line(136,48,136,50);
	$pdf->Line(142,48,142,50);
	$pdf->Line(148,48,148,50);
	
	$pdf->Line(166,48,166,50);
	$pdf->Line(172,48,172,50);
	$pdf->Line(178,48,178,50);	
	
	
	$header=array('ALUMNOS','EVALUACIÓN','NL','EVALUACIÓN','NL','EVALUACIÓN','NL','DE');	
	$w=array(92,24,6,24,6,24,6,10);
	$tx=array(12,8,8,8,8,8,8,8);
	//Cabeceras
	for($i=0;$i<count($header);$i++){
		$pdf->SetFont('Arial','',$tx[$i]);
		$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
	}	
	$pdf->Ln();
	
	//segundo encabezado----------------
	$pdf->SetFont('Arial','',9);
	$header=array('','','','','','');	
	$w=array(20,72,30,30,30,10);
	//Cabeceras
	for($i=0;$i<count($header);$i++)
		$pdf->Cell($w[$i],0,$header[$i],0,0,'C');
	$pdf->Ln();
	
	//crear la tabla con los registros
	
	$control = 1;
	$y = 54;
	
	do{
		if($control == 37){
			$pdf->AddPage();
			$pdf->SetX(188);
			$pdf->Cell(100,10,'Pág. '.$pdf->PageNo());
			$pdf->Ln(15);
			$control = 0;
			$y = 34;
			
			//lineas de separacion****
			$pdf->Line(106,28,106,30);
			$pdf->Line(112,28,112,30);
			$pdf->Line(118,28,118,30);
			
			$pdf->Line(136,28,136,30);
			$pdf->Line(142,28,142,30);
			$pdf->Line(148,28,148,30);
		
			$pdf->Line(166,28,166,30);
			$pdf->Line(172,28,172,30);
			$pdf->Line(178,28,178,30);	
			
			//por cada pagina imprimir de nuevo el encabezado
			$pdf->SetFont('Arial','',12);
			$header=array('ALUMNOS','EVALUACIÓN','NL','EVALUACIÓN','NL','EVALUACIÓN','NL','DE');	
			$w=array(92,24,6,24,6,24,6,10);
			$tx=array(12,8,8,8,8,8,8,8);
			//Cabeceras
			for($i=0;$i<count($header);$i++){
				$pdf->SetFont('Arial','',$tx[$i]);
				$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
			}
			$pdf->Ln();
			//segundo encabezado----------------
			$pdf->SetFont('Arial','',9);
			$header=array('','','','','','');	
			$w=array(20,72,30,30,30,10);
			//Cabeceras
			for($i=0;$i<count($header);$i++)
				$pdf->Cell($w[$i],0,$header[$i],0,0,'C');
			$pdf->Ln();
		}
	
		//lineas de separacion en zona de repeticion****
		$pdf->Line(106,$y,106,$y+2);
		$pdf->Line(112,$y,112,$y+2);
		$pdf->Line(118,$y,118,$y+2);
		$pdf->Line(124,$y,124,$y+2);
		
		$pdf->Line(136,$y,136,$y+2);
		$pdf->Line(142,$y,142,$y+2);
		$pdf->Line(148,$y,148,$y+2);
		$pdf->Line(154,$y,154,$y+2);
	
		$pdf->Line(166,$y,166,$y+2);
		$pdf->Line(172,$y,172,$y+2);
		$pdf->Line(178,$y,178,$y+2);
		$pdf->Line(184,$y,184,$y+2);
		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell($w[0],6,$row_Recordset1['Cedula'],1,0,'LR');
		$pdf->Cell($w[1],6,$row_Recordset1['Apellidos'].", ".$row_Recordset1['Nombres'],1,0,'LR');
		$pdf->Cell($w[2],6,'',1,0,'LR');
		$pdf->Cell($w[3],6,'',1,0,'LR');
		$pdf->Cell($w[4],6,'',1,0,'LR');
		$pdf->Cell($w[5],6,'',1,0,'LR');
		$pdf->Ln();
		$control++;
		$y=$y+6;
	}while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
	//Línea de cierre
	$pdf->Cell(array_sum($w),0,'','T');
	
	//pie de pagina, final
	
	$pdf->SetFont('Arial','',9);
	$pdf->Ln(3);
	$pdf->Cell(100,10,'NOTA:  Este formulario no debe llevar tachadura ni enmienda');
	
	$pdf->Ln(20);
	$pdf->SetX(20);
	$pdf->Cell(100,10,'___________________________________________');
	$pdf->SetX(110);
	$pdf->Cell(100,10,'___________________________________________');
	$pdf->Ln(5);
	$pdf->SetX(44);
	$pdf->Cell(100,10,'EL PROFESOR');
	$pdf->SetX(125);
	$pdf->Cell(100,10,'JEFE DE CONTROL DE ESTUDIOS');
	
	$pdf->Output();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
</head>

<body>

</body>
</html>
<?php
	mysql_free_result($Recordset1);		
?>