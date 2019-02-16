<?php
 //   require("resource/php/buscarEstudiante.php");
    require("resource/php/conexion.php");
?> 
<?php 

 session_start();
 
//    include_once('../lib/Conectar.php');
//	$loginUsername = $_POST['wsCedula']; 
//	$password = $_POST['wsPassWord'];
    $Data = stripslashes($_POST['Data']);
    $JSON = json_decode($Data);
    $loginUsername = $JSON->Cedula;
    $password=$JSON->PassWord;
	$tk = hash_hmac('sha256', $loginUsername, 'c0d.Pr0f3s@unim4r');	
	$flag = FALSE;
	$cant = 0;
	$url = "http://192.168.20.1/www/ServicioProfesores/authenticationNueva.php?ced=".$loginUsername.'&dec='.$tk;
//	$url = "profesores.campus.unimar.edu.ve/authenticationNueva.php?ced=".$loginUsername.'&dec='.$tk;
//	$url = "profesores.campus.unimar.edu.ve/authenticationNueva234.php?ced=".$loginUsername.'&dec='.$tk;
	while ($flag != TRUE && $cant <= 3){
		$cant++;				
		$ch = curl_init($url);					
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		if (strstr($output, "Forbidden") == FALSE) $flag = TRUE;
		sleep(1);
	}				
	curl_close($ch);
    $Datos=stripslashes($output);
	$JSon=json_decode($Datos);
//	$output=$JSon->Autorizacion;	
	$Sesion=$JSon->Sesion;
	$output=$JSon->Clave;
//	exit($output);
	$salt = substr($output,0,20);
	$temp = $salt.hash('sha256', $password.$salt);				
	//echo $temp.' - '.$output;
	if ($temp == $output) 		
	  {$_SESSION['Cedula']=	$loginUsername;
	   $_SESSION['Origen']= 'Local';
	   $_SESSION['Sesion']= $Sesion;
	   $_SESSION['IP']= $JSon->Remoto;
	   if(substr($JSon->Remoto, strlen($JSon->Remoto)-5) == '20.10')
	     {unset($_SESSION['IP']);
		  echo '-2';}
		else 
	     {			 echo '0';
//		 	   exit($_SESSION['Cedula'].'=='.$_SESSION['Origen'].'=='.$_SESSION['Sesion'].'=='.$_SESSION['IP']);

}}
	  else 
	   {echo '-1';}
 ?>
