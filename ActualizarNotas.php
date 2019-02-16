<?php
  session_start();
  if (!isset($_SESSION['Profesor']))
    {$JSon['Codigo']=-1;
     $JSon['Mensaje']='No ha hecho LogIn';
	 exit(json_encode($JSon));}
  include_once('..conexion.php'); 
  $Data = stripslashes($_POST['Data']);
  $JSON = json_decode($Data);
  $Ciclos = count($JSON->Estudiantes);
  $JSon['Actualizables'] = $Ciclos;
  $JSon['Actualizados']= 0;
  if($Ciclos == 0) 
    {$JSon['Codigo']=0;
     $JSon['Mensaje']='No hay Notas para actualizar';
     $db->Close(); #cerrar la conexion
     exit(json_encode($JSon)); }
  for ($i=0; $i < $Ciclos; $i++)
   {$sql = "Select *
              From Unimar.EstudiantesHistorico
		     Where EstudiantesHistorico.Cedula = ".$JSON->Estudiantes[$i]->Cedula." 
		       and EstudiantesHistorico.Ano = '".substr($JSON->Periodo, 0,4)."'
		       and EstudiantesHistorico.Periodo = '".substr($JSON->Periodo, 4,1)."'
		       and EstudiantesHistorico.NivelEstudios = '".substr($JSON->Periodo, 5,1)."'
		       and EstudiantesHistorico.Asignatura = '".substr($JSON->Asignatura, 1)."'
		       and EstudiantesHistorico.Secuencia = '".substr($JSON->Asignatura, 0,1)."'
		       and EstudiantesHistorico.Turno = '".substr($JSON->Seccion, 1,1)."'
		       and EstudiantesHistorico.Seccion = '".substr($JSON->Seccion, 2)."'";
    $resultSetAsignaturas = odbc_do($db, $sql);	
	if($JSON->Estudiantes[$i]->Nota == 'AP') $JSON->Estudiantes[$i]->Nota= 50 ;
	if($JSON->Estudiantes[$i]->Nota == 'RP') $JSON->Estudiantes[$i]->Nota= 51 ;
	if(odbc_result($resultSetAsignaturas, strtoupper($JSON->Corte)) != $JSON->Estudiantes[$i]->Nota)
	  {
    $sql = "Update Unimar.EstudiantesHistorico
               Set ".$JSON->Corte." = ".$JSON->Estudiantes[$i]->Nota." 
		    Where EstudiantesHistorico.Cedula = ".$JSON->Estudiantes[$i]->Cedula." 
		      and EstudiantesHistorico.Ano = '".substr($JSON->Periodo, 0,4)."'
		       and EstudiantesHistorico.Periodo = '".substr($JSON->Periodo, 4,1)."'
		       and EstudiantesHistorico.NivelEstudios = '".substr($JSON->Periodo, 5,1)."'
		       and EstudiantesHistorico.Asignatura = '".substr($JSON->Asignatura, 1)."'
		       and EstudiantesHistorico.Secuencia = '".substr($JSON->Asignatura, 0,1)."'
		       and EstudiantesHistorico.Turno = '".substr($JSON->Seccion, 1,1)."'
		       and EstudiantesHistorico.Seccion = '".substr($JSON->Seccion, 2)."'";
    $resultSetAsignaturas = odbc_exec($db, $sql);
	if(odbc_num_rows($resultSetAsignaturas) > 0) $JSon['Actualizados']= $JSon['Actualizados']+1;}}
  $JSon['Codigo']=0;
  $JSon['Mensaje']='Notas Actualizadas ('.$JSon['Actualizados'].' de '.$Ciclos.')';
  odbc_close(); #cerrar la conexion
  exit(json_encode($JSon)); 
	 ?> 
