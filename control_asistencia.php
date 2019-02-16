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
<?php require('fpdf16/fpdf.php');?>
<?php
	//parametros
	
	/*evitar sql injection en variable tipo cadena*/
	$datos = isset($_GET['asig']) ? mysql_real_escape_string($_GET['asig']) : '';
	$sec = isset($_GET['sec']) ? mysql_real_escape_string($_GET['sec']) : '';
		$var_periodo = $_GET['Periodo'];
		$var_ano = $_GET['Ano'];
		$var_nivel = $_GET['NivelEstudios'];
	
	list($asignatura, $descrip, $secuencia) = split('//', $datos);
	list($turno, $seccion) = split('-', $sec);
	$cedula = $_SESSION['cedula'];
	
	$nomasig = $asignatura.' - '.$descrip;
	$nomtur = $turno.'-'.$seccion;
	$nomprof = $cedula.' - '.$_SESSION['nombre'].' '.$_SESSION['apellido'];
	
	//query lista de alumnos
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
Inner Join Horas ON Horas.Hora = PlanificacionHorarios.Hora
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



	/*CREACION DEL REPORTE PDF*/	
	$pdf= new FPDF('P', 'mm', 'Letter');	
	$pdf->Open();
	
	//configuracion del codumento general
	$pdf->SetLeftMargin(8);
	$pdf->SetAutoPageBreak(true,30);
	$pdf->SetTopMargin(8);
	$pdf->SetFont('Arial','',11);
	$pdf->SetTitle('Planilla de Control de Asistencia');
	$pdf->AddPage();
	
	/*colocar informacion de encabezado*/	
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(200,10,'UNIVERSIDAD DE MARGARITA',0,0,'C');
	$pdf->Ln(5);
	$pdf->Cell(200,10,'VICERRECTORADO ACADÉMICO',0,0,'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(200,10,'Planilla de Control de Asistencia',0,0,'C');
	
		
	$pdf->Ln(11);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,10,'ASIGNATURA: ',0,0);
	$pdf->SetX(33);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(100,10,$nomasig,0,0);
	$pdf->SetX(120);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(100,10,'Horario',0,0);
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,10,'SECCIÓN: ',0,0);
	$pdf->SetX(33);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(100,10,$nomtur,0,0);
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,10,'PROFESOR: ',0,0);
	$pdf->SetX(33);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(100,10,$nomprof,0,0);
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,10,'IMPRESO: ',0,0);
	$pdf->SetX(33);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(100,10,date("d-m-Y - g:i a"),0,0);
	
		
	//tabla de observaciones
	$pdf->Ln(12);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(105,4,'Observaciones:',1,0,'LR');
	$pdf->Ln();
	$pdf->Cell(105,4,'',1,0,'LR');
	$pdf->Ln();
	$pdf->Cell(105,4,'',1,0,'LR');
	$pdf->Ln();
	$pdf->Cell(105,4,'',1,0,'LR');
	$pdf->Ln();
	$pdf->Cell(105,4,'',1,0,'LR');
	
	//tabla de horarios de seccion	****
	$pdf->SetY(31);
	$header=array('DIA','AULA','Desde','Hasta');	
	$w=array(20,20,20,20);
	$anchoCelda = 135;
	//Cabeceras
	for($i=0;$i<count($header);$i++){
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX($anchoCelda);
		$pdf->Cell($w[$i],4,$header[$i],0,0,'L');
		$anchoCelda = $anchoCelda+20;
	}	
	$pdf->Ln(50);
	$pdf->SetY(35);
	$anchoCelda = 135;
	do{
		
		$pdf->SetFont('Arial','',7);
		$pdf->SetX($anchoCelda);
		$pdf->Cell($w[0],3,$row_Recordset2['Descripcion'],0,0,'LR');
		$pdf->Cell($w[1],3,$row_Recordset2['Aula'],0,0,'LR');
		$pdf->Cell($w[2],3,$row_Recordset2['Desde'],0,0,'LR');
		$pdf->Cell($w[3],3,$row_Recordset2['Hasta'],0,0,'LR');
		$pdf->Ln();
	}while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
	
	//$pdf->Ln(30);
	$pdf->SetY(75);
	
	//tabla de alumnos ***
	$pdf->SetX(124);
	$pdf->Cell(72,4,'DÍAS',1,0,'C');
	$pdf->Ln();
	$header=array('','CÉDULA','APELLIDOS Y NOMBRES','','','','','','','','','','','','','TI');	
	$w=array(6,20,90,6,6,6,6,6,6,6,6,6,6,6,6,6);	
	//Cabeceras
	for($i=0;$i<count($header);$i++){
		$pdf->SetFont('Arial','B',7);		
		$pdf->Cell($w[$i],5,$header[$i],1,0,'L');
		$anchoCelda = $anchoCelda+20;
	}
	$pdf->Ln();
	
	$cont = 1;
	$control = 1;
	
	do{
		if($control == 33){
			$pdf->AddPage();
			//tabla de alumnos ***
			$header=array('','CÉDULA','APELLIDOS Y NOMBRES','','','','','','','','','','','','','TI');	
			$w=array(6,20,90,6,6,6,6,6,6,6,6,6,6,6,6,6);	
			//Cabeceras
			for($i=0;$i<count($header);$i++){
				$pdf->SetFont('Arial','B',7);		
				$pdf->Cell($w[$i],5,$header[$i],1,0,'L');
				$anchoCelda = $anchoCelda+20;
			}
			$pdf->Ln();
			
			$control = 0;
		}
		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell($w[0],5,$cont,1,0,'LR');
		$pdf->Cell($w[1],5,$row_Recordset1['Cedula'],1,0,'LR');
		$pdf->Cell($w[2],5,$row_Recordset1['Apellidos']." ".$row_Recordset1['Nombres'],1,0,'LR');
		$pdf->Cell($w[3],5,'',1,0,'LR');
		$pdf->Cell($w[4],5,'',1,0,'LR');
		$pdf->Cell($w[5],5,'',1,0,'LR');
		$pdf->Cell($w[6],5,'',1,0,'LR');
		$pdf->Cell($w[7],5,'',1,0,'LR');
		$pdf->Cell($w[8],5,'',1,0,'LR');
		$pdf->Cell($w[9],5,'',1,0,'LR');
		$pdf->Cell($w[10],5,'',1,0,'LR');
		$pdf->Cell($w[11],5,'',1,0,'LR');
		$pdf->Cell($w[12],5,'',1,0,'LR');
		$pdf->Cell($w[13],5,'',1,0,'LR');
		$pdf->Cell($w[14],5,'',1,0,'LR');
		$pdf->Cell($w[15],5,'',1,0,'LR');
		$pdf->Ln();
		
		$cont++;
		$control++;
	}while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
	//Línea de cierre
	$pdf->Cell(array_sum($w),0,'','T');
	
	$pdf->Ln(4);
	$pdf->SetFont('Arial','B',7);
	$pdf->SetX(35);
	$pdf->Cell(100,10,'Firma del Profesor',0,0);
	$pdf->SetX(137);
	$pdf->Cell(100,10,'Vto. Bno. del Decano',0,0);
	
	//salida del documento
	$pdf->Output();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Planilla de Control de Asistencia</title>
</head>

<body>

</body>
</html>
<?php
	mysql_free_result($Recordset1);
	mysql_free_result($Recordset2);
?>