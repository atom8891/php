<?php 
$ced = $_GET['Cedula'];
//$bot=$_POST['buscar'];
include('conexion.php');
/*require('../odbcsql/buscarEstudiante.sql');*/
require('../libs/fpdf18/fpdf.php');//Llama al archivo que contiene la clase
 $sqli="Select Nacionalidad, Cedula, Apellidos, Nombres, 
       decode(FechaNacimiento, Null, Null, substr(FechaNacimiento,9,2)||'/'||substr(FechaNacimiento,6,2)||'/'||substr(FechaNacimiento,1,4)) as FechaNacimiento,
       Sexo, CiudadNacimiento, EstadoCivil, AnoIngreso, PeriodoIngreso,
       decode(FechaIngreso, Null, Null, substr(FechaIngreso,9,2)||'/'||substr(FechaIngreso,6,2)||'/'||substr(FechaIngreso,1,4)) as FechaIngreso,
       Titulo, Institucion, AnoGrado, DireccionPermanente, CiudadPermanente, TelefonoPermanente,CorreoElectronico, TelefonoCelular,
       DireccionLocal, CiudadLocal, TelefonoLocal, TelefonoContacto,LugarTrabajo, TelefonoTrabajo
         From Unimar.Estudiantes
		Where Estudiantes.Cedula = ".$ced;


//$hoy = getdate();
//$date = $hoy['year'].'/'.$hoy['mon'].'/'.$hoy['mday'];

//////////////////////////////////////////////////////////////////////////////////
$sql= odbc_exec($enlace, $sqli);

//	$numero2 = odbc_num_rows($sql);
odbc_fetch_row($sql);
//	$row2 = odbc_fetch_row($sql);

	$nac =odbc_result($sql,1);//toma el valor del nacionalidad del estudiante
	$ced =odbc_result($sql,2);//toma el valor del Cedula del estudiante
	$ape =odbc_result($sql,3);//toma el valor del apellido del estudiante
	$nom =odbc_result($sql,4);//toma el valor del nombre del estudiante
 	$fn = odbc_result($sql,5);//toma la fecha de nacimiento del estudiante
 	$gen =odbc_result($sql,6);//toma el sexo del estudiante
	$cn = odbc_result($sql,7);//toma la ciudad de nacimiento del estudiante
  	$eci =odbc_result($sql,8);//toma el estado civil del estudiante
    $nutb = odbc_result($sql,12);//toma el valor del numero de titulo de bachiller del estudiante
 	$ins = odbc_result($sql,13);//toma el valor del instituto de bachiller del estudiante
 	$gradb =odbc_result($sql,14);//toma el año de graduacion
 	$d1 = odbc_result($sql,15);//toma el valor de direccion permanente
 	$c1 =odbc_result($sql,16);//toma el valor de la ciudad permanente del estudiante 	
 	$tel =odbc_result($sql,17);//toma el valor del telefono permanente del estudiante
 	$em = odbc_result($sql,18);//toma el valor del email del estudiante
	$tefcel =odbc_result($sql,19);//toma el valor del telefono local del estudiante
	$d2 = odbc_result($sql,20);//toma la direccion local del estudiante
 	$c2 = odbc_result($sql,21);
 	$tefloc =odbc_result($sql,22);//toma el valor del telefono local del estudiante
 	$tc = odbc_result($sql,23);//toma el valor del telefono de contacto del estudiante
 	$trab =odbc_result($sql,24);//toma el valor de donde trabaja el estudiante
 	$ttrab =odbc_result($sql,25);//toma el valor del telefono del trabajo
	//$car =odbc_result($sql,26);;//toma la carrera del estudiante
	//$cinst =odbc_result($sql,26);;//toma la carrera del estudiante

  
 	//$n = odbc_result($sql,22);//toma el valor del precio a mostrar
	/*$datosest = odbc_exec($enlace, $sqli); 	
	$num = odbc_num_rows($datosest);
	$rowest = odbc_fetch_row($datosest);
	$nomrest = $rowest[1];
	$aperet = $rowest[2];
	$tel = $rowest[5];
	$em = $rowest[9];
	$car = $rowest[8];*/

/*


if ($tip=='programa'){//realizará consulta para el caso de programa
	$can_ind3 = mysql_query("SELECT clientes.RIF, nombrecomer, direccion, compra.codcompra, compra.fechainicio, compra.fechatermino, compra.precio, programa.nombreprograma, programa.frecuencia, programa.comprende FROM `clientes` INNER JOIN (`compra` INNER JOIN `programa` ON compra.codcompra = programa.codcompra) ON compra.rif = clientes.RIF WHERE compra.codcompra = $c", $enlace); // cantidad de indicadores para la cuña tipo programa
	$numero3 = mysql_num_rows($can_ind3);
	$row3 = mysql_fetch_row($can_ind3);
	$ced =$row3[0];//toma el valor del rif del cliente a mostrar
	$nombre=$row3[1];//toma el valor del nombre comercial del cliente a mostrar
	$nacion =$row3[2];//toma el valor del direccion del cliente a mostrar
	$carrera=$row3[6];//toma el valor del precio a mostrar
	$datoscli = mysql_query("SELECT * FROM `clientes` WHERE clientes.RIF='$clirif'", $enlace); 	
	$num = mysql_num_rows($datoscli);
	$rowcli = mysql_fetch_row($datoscli);
	$nomres = $rowcli[4];
	$figem = $rowcli[5];
	$tel = $rowcli[7];
	$pro = $rowcli[9];
	$venta = "Inicia el ".$row3[4]." y Finaliza el ".$row3[5]." en el programa ".$row3[7]." con frecuencia ".$row3[8]." comprende a: ".$row3[9];

}

if ($tip=='rotativa'){//realizará consulta para el caso de movil
	$can_ind4 = mysql_query("SELECT clientes.RIF, nombrecomer, direccion, compra.codcompra, compra.fechainicio, compra.fechatermino, compra.precio, rotativa.frecuencia, rotativa.selectiva, rotativa.suelta FROM `clientes` INNER JOIN (`compra` INNER JOIN `rotativa` ON compra.codcompra = rotativa.codcompra) ON compra.rif = clientes.RIF WHERE compra.codcompra = $c", $enlace); // cantidad de indicadores para la cuña tipo rotativa
	$numero4 = mysql_num_rows($can_ind4);
	$row4 = mysql_fetch_row($can_ind4);
	$clirif =$row4[0];//toma el valor del rif del cliente a mostrar
	$nomcom =$row4[1];//toma el valor del nombre comercial del cliente a mostrar
	$domcli =$row4[2];//toma el valor del direccion del cliente a mostrar
	$pre=$row4[6];//toma el valor del precio a mostrar
	$datoscli = mysql_query("SELECT * FROM `clientes` WHERE clientes.RIF='$clirif'", $enlace); 	
	$num = mysql_num_rows($datoscli);
	$rowcli = mysql_fetch_row($datoscli);
	$nomres = $rowcli[4];
	$figem = $rowcli[5];
	$tel = $rowcli[7];
	$pro = $rowcli[9];
	$venta = "Inicia el ".$row4[4]." y Finaliza el ".$row4[5]." con frecuencia de ".$row4[7]." siendo ".$row4[9]." sueltas";
}
*/



/////////////////////////////////////////////////////////////////////////////////
class PDF extends FPDF
{
/*// Cabecera de página
function Header()
{
    // Logo
    $this->Image('logo_unim.png',10,8,33);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    $this->Cell(80);
    // Título
    $this->Cell(30,10,'Planillas de Inscripcion',1,0,'C');
    // Salto de línea
    $this->Ln(20);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}*/
}
ob_end_clean();
$pdf=new FPDF();
$pdf->AliasNbPages();
$pdf->AddPage();
//$pdf->Cell(190,40, $pdf->Image('/../img/logo_unim.png','','', 100) ,0,"C");
//$pdf->Image('/../img/logo_unim.png');
$pdf->SetY(20);
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(190,10,'CARTA COMPROMISO',0,'C');
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(190,10,utf8_decode(' Quien suscribe '.utf8_encode($nom).' '.utf8_encode($ape).', de nacionalidad '.utf8_encode($nac).' y titular de la cédula:'.$ced.' por medio del presente declaro lo siguiente:'),0,'J');
$pdf->MultiCell(190,10,utf8_decode('1. Que me encuentro consciente y asumo plenamente la responsabilidad y el deber de darle
cumplimiento a todo lo relacionado, conexo y a fin con lo establecido con la UNIVERSIDAD DE MARGARITA,
 en cuanto al cumplimiento de las exigencias administrativas, arancelarias y demás recaudos documentales, normas internas y mis deberes como estudiante de esta Casa de Estudio. '),0,'J');
$pdf->Ln();
$pdf->MultiCell(190,10,utf8_decode('2. Que me encuentro consciente y asumo plenamente la responsabilidad y el deber de darle
cumplimiento a todo lo relacionado, conexo y a fin con lo establecido en la Ley de Servicio
Comunitario del Estudiante de Educacion Superior y con la norma interna sobre Servicio
Comunitario de la Universidad de Margarita, como requisito para la obtención del titulo de
Educación Universitaria, siendo que este no creará derechos u obligaciones de carácter
laboral y debe prestarse sin remuneración alguna. Esta obligación se generará partir de la
aprobación del cincuenta por ciento (50%) del pensum de la carrera.'),0,'J');
$pdf->Ln();
$pdf->MultiCell(190,10,utf8_decode('3. Que me encuentro consciente y asumo plenamente la responsabilidad y el deber de darle
cumplimiento a todo lo relacionado, conexo y a fin con lo establecido por la
UNIVERSIDAD DE MARGARITA, en lo atinente a la aprobación obligatoria como
requisito para optar al grado correspondiente de los conocimientos suficientes de sus
estudiantes en el manejo instrumental de un idioma extranjero equivalente al nivel II,
correspondiente a la carrera de Idiomas Modernos. Esta obligación se generá a partir de la
aprobación del tercer semestre o trimestre del pensum de la carrera.'),0,'J');
$pdf->Ln();$pdf->SetFont('Arial','',6);
//$pdf->MultiCell(190,10,'En el Valle del Espíritu Santo, a los' '____' 'días del mes de' '_______''de' '_________',0, 'C');
//$pdf->MultiCell(190,10,'En el Valle del Espíritu Santo, a los '.$hoy['mday']' días del mes de '.$hoy['mon'].'de '.$hoy['year']'.',0);
$pdf->Cell(95,10,'FIRMA________________',0,'C');$pdf->Cell(95,10,'CEDULA_______________',0,'C');

 


$pdf->AddPage();
$pdf->SetY(30);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(190,10,'CONTROL DE ESTUDIOS',0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(1);//Hacia la derecha
$pdf->SetFont('Arial','',10);
$pdf->Cell(95,10,'NACIONALIDAD: '.$nac,1,0,'L');$pdf->Cell(95,10,'CEDULA: '.$ced ,1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'NOMBRE: '.utf8_encode($nom),1,0,'L');$pdf->Cell(95,10,'APELLIDO: ' .utf8_encode($ape),1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha

$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'FECHA DE NACIMIENTO: ' .$fn,1,0,'L');$pdf->Cell(95,10,'GENERO: '.$gen,1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'CARRERA: ',1,0,'L');$pdf->Cell(95,10,'EMAIL: ' .$em,1,0,'L');
$pdf->Ln();//SALTO DE LINEA 
$pdf->Cell(1);//Hacia la derecha

$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha

$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'ESTADO CIVIL: ' .$eci,1,0,'L');$pdf->Cell(95,10,'TELEFONO LOCAL: '.$tefloc ,1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha

$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'DIRECCION: '.$d1,1,'L');$pdf->Cell(95,10,'CIUDAD: '.$C1,1,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'DIRECCION LOCAL: '.$d2,1,'L');$pdf->Cell(95,10,'CIUDAD LOCAL: '.$C2,1,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->SetFont('Arial','B',12);
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha

$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(190,10,'ESTUDIOS REALIZADOS',0,'C');

$pdf->Cell(1);//Hacia la derecha
$pdf->SetFont('Arial','',10);
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'TITULO DE BACHILLER:',1,'L');$pdf->Cell(95,10,'NUMERO DE TITULO: '.$nutb,1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'INSTITUCION: '.$ins,1,'L');$pdf->Cell(95,10,'AÑO DE GRADUACION: '. $gradb,1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//HACIA LA DERECHA
$pdf->Cell(190,10,'CIUDAD:',1,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->SetFont('Arial','B',12);
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->MultiCell(190,10,'OTROS ESTUDIOS',0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(190,10,'(si los posee)',0,'C');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->SetFont('Arial','',10);
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->SetFont('Arial','',10);
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'TITULO',1,'L');$pdf->Cell(95,10,'ESPECIALIDAD:',1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'INSTITUCION:',1,'L');$pdf->Cell(95,10,'CIUDAD:',1,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//HACIA LA DERECHA
$pdf->SetFont('Arial','B',12);
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->MultiCell(190,10,'trabajo Actual',0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'DIRECCION: '.$trab,1,0,'L');$pdf->Cell(95,10,'TELEFONO: '.$Ttrab,1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha

////////////////////////////////////////FINAL DE LA ORDEN ////////////////////////////////
//Posición: a 2 cm del final 

$pdf->Ln();//SALTO DE LINEA
$pdf->SetX(100); 
$pdf->SetFont('Arial','BI',8); 
$pdf->Cell(20,20,'RECIBIDO POR:_____________________                               Firma:______________________','',1,'C'); 
//$pdf->Cell(60,10,'Fecha:'.$date,1,0,'C');
$pdf->Ln();//SALTO DE LINEA
$pdf->AddPage();
$pdf->SetY(30);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(190,10,'BIENESTAR ESTUDIANTIL',0,'C');
$pdf->SetFont('Arial','',10);
$pdf->SetFont('Arial','',10);
$pdf->Cell(95,10,'NACIONALIDAD: '.$nac,1,0,'L');$pdf->Cell(95,10,'CEDULA: '.$ced ,1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'NOMBRE: '.utf8_encode($nom),1,0,'L');$pdf->Cell(95,10,'APELLIDO: ' .utf8_encode($ape),1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha

$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'FECHA DE NACIMIENTO: ' .$fn,1,0,'L');$pdf->Cell(95,10,'GENERO: '.$gen,1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'CARRERA: ',1,0,'L');$pdf->Cell(95,10,'EMAIL: ' .$em,1,0,'L');
$pdf->Ln();//SALTO DE LINEA 
$pdf->Cell(1);//Hacia la derecha

$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha

$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'ESTADO CIVIL: ' .$eci,1,0,'L');$pdf->Cell(95,10,'TELEFONO LOCAL: '.$tefloc ,1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha

$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'DIRECCION: '.$d1,1,'L');$pdf->Cell(95,10,'CIUDAD: '.$C1,1,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'DIRECCION LOCAL: '.$d2,1,'L');$pdf->Cell(95,10,'CIUDAD LOCAL: '.$C2,1,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->SetFont('Arial','B',12);
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->SetFont('Arial','B',12);
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->MultiCell(190,10,'HISTORIA MEDICA',0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'ALERGIAS:',1,'L');$pdf->Cell(95,10,'TRATAMIENTO MEDICO:',1,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'DICAPACIDAD:',1,'L');$pdf->Cell(95,10,'TELEFONO DE EMERGENCIA: '.$tc,1,0,'L');
$pdf->Ln();//SALTO DE LINEA
$pdf->Cell(1);//Hacia la derecha
$pdf->Cell(95,10,'CONTACTO DE EMERGENCIA:',1,0,'L');$pdf->Cell(95,10,'PARENTESCO:',1,0,'L');
$pdf->Ln();//SALTO DE LINEA

$pdf->Ln();//SALTO DE LINEA
$pdf->Ln();//SALTO DE LINEA
$pdf->SetX(100); 
$pdf->SetFont('Arial','BI',8); 
$pdf->Cell(20,20,'RECIBIDO POR:__________________________                                Firma:__________________________','',1,'C');
/**/

$pdf->Output();
//exit($ced.'<')	;
//Arial italic 8 
?>