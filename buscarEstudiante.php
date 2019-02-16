<?php 
	include('conexion.php');
	$ced = $_POST['Cedula']; //Toma el valor del textfield Cedula
	include('../sql/buscarEstudiante.sql');
//	$bot = $_POST['Buscar'];
	//if (isset($_POST['Buscar']) !=""){
//	exit($sqli);
		$Estudiantesql = odbc_exec($enlace,$sqli); // cantidad de indicadores resgistrados
		if (odbc_num_rows($Estudiantesql)==0) {
			$json['Codigo']=1;
			$json['Mensaje']= 'Cédula no registrada en la base de datos';
		    odbc_close($enlace);
			exit (json_encode($json));}
		 else
          {$i=0;
           while($i++ < odbc_num_fields($Estudiantesql))
             {$Estudiante[odbc_field_name($Estudiantesql,$i)]=utf8_encode(odbc_result($Estudiantesql,$i));}
          $JSon['Codigo']=0;
          $JSon['Estudiante']=$Estudiante;
          odbc_close($enlace);
          exit(json_encode($JSon));}
  //    }
		/*
		//$row = odbc_fetch_row($Estudiantesql);
		if ($numero == 1){
				 		
		
 	  		 		
			}else
	 			echo "<script> alert('C&eacute;dula no registrada en la Base de Datos') </script>";
		}
 	$nom = $_POST['Nombre']; //Toma el valor del textfield nombre
 	$ape = $_POST['Apellido']; //Toma el valor del textfield apellido
 	$nac = $_POST['Nacionalidad']; //Toma el valor del textfield Nacionalidad
 	$tel = $_POST['Telefono']; //Toma el valor del textfield Telefono
 	$tefloc = $_POST['Telefonolocal']; //Toma el valor del textfield telefonolocal
 	$fn = $_POST['FechadeNacimiento']; //Toma el valor del textfield fechadenacimiento
 	$cn = $_POST['CiudadNac']; //Toma el valor del textfield CiudadNac
 	$car = $_POST['Carrera']; //Toma el valor del textfield carrera
 	$em = $_POST['email']; //Toma el valor del textfield email
 	$gen = $_POST['Genero']; //Toma el valor del listbox Genero
 	$eci = $_POST['edocivil']; //Toma el valor del listbox edocivil
 	$d = $_POST['Direccion']; //Toma el valor del textarea Direccion
 	$d2 = $_POST['Direccion2']; //Toma el valor del textfield Direccion2
 	$c1 = $_POST['Ciudadper']; //Toma el valor del textarea ciudadper
 	$c2 = $_POST['Ciudadloc']; //Toma el valor del textfield ciudadloc
 	$tb = $_POST['Titulob']; //Toma el valor del textfield Titulob
 	$nt = $_POST['NrodeTitulo']; //Toma el valor del textfield NrodeTitulo
 	$ins = $_POST['Institución']; //Toma el valor del textfield Institución
 	$gradb = $_POST['AñoGradb']; //Toma el valor del textfield AñoGradb
 	$tlfin = $_POST['Carrera']; //Toma el valor del textfield carrera
 	$us = $_POST['email']; //Toma el valor del textfield email
 	$n = $_POST['Genero']; //Toma el valor del listbox Genero
 	$bot1 = $_POST['Imprmir'];

	if (isset($bot1) !=""){
		$can_ind = odbc_exec($enlace,"SELECT * FROM `ESTUDIANTES` WHERE `CEDULA` = '$ced'"); // cantidad de indicadores resgistrados
		$numero = odbc_num_rows($can_ind);
	if (isset($bot1) !=""){
		$can_ind = odbc_exec($enlace,"SELECT * FROM `ESTUDIANTES` WHERE `CEDULA` = '$ced'"); // cantidad de indicadores resgistrados
		$numero = odbc_num_rows($can_ind);
		if ($numero == 1){
			if ($pu == $puc){
			    $result = odbc_exec($enlace,"UPDATE `ESTUDIANTES` SET `NOMBRES` = '$nom', `APELLIDOS` = '$ape', `NACIONALIDAD` = '$nac' , ´TELEFONOCELULAR´='$tel', ´TELEFONOLOCAL´='$tefloc',´FECHANACIMIENTO´='$fn',`CIUDADNACIMIENTO` = '$cn',`TITULO` = '$tb', `INSTITUCION` = '$ins',`ANOGRADO` = '$gradb',`SEXO` = '$gen'  '$cn',`TITULO` = '$tb', `INSTITUCION` = '$ins',`ANOGRADO` = '$gradb',`DIRECCIONPERMANENTE` = '$d',`CIUDADPERMANENTE` = '$c1',`DIRECCIONLOCAL` = '$d2', `CIUDADPERMANENTE` = '$c2', `CORREOELECTRONICO` = '$em'WHERE `CEDULA` = '$ced'"); 	
				$c="";					
				echo "<script> alert('La informaci&oacute;n se ha actualizado en la base de datos exitosamente.') </script>";
			}else
				echo "<script> alert('Las contrase&ntilde;as que ha escrito no coinciden. Vuelva a escribirlas.') </script>";
 		}else
 			echo "<script> alert('C&eacute;dula no registrada en la Base de Datos') </script>";
		}
			*/	 				 
?>
