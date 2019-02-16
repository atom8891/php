<?php
  session_start();
  if (!isset($_SESSION['Profesor']))
    {$JSon['Codigo']=-1;
     $JSon['Mensaje']='No ha hecho LogIn';
	 exit(json_encode($JSon));}
  $Data=stripslashes($_POST['Data']);
  $JSON=json_decode($Data);
  include_once('./conexion.php'); 
	 $sql = "Select Decode(Planificacion.Impresa, True, 'V','F')||Planificacion.Turno||Planificacion.Seccion as Seccion, 
	                Planificacion.Turno||'-'||Planificacion.Seccion as Descripcion, Impresa
               From UniMar.PeriodosActivosAcademicos, UniMar.Planificacion, UniMar.Extensiones
              Where PeriodosActivosAcademicos.Ano||PeriodosActivosAcademicos.Periodo||
				    PeriodosActivosAcademicos.NivelEstudios = '".$JSON->Periodo."'
                and Extensiones.Activa
                and Planificacion.Extension = Extensiones.Extension
                and Planificacion.NivelEstudios = PeriodosActivosAcademicos.NivelEstudios 
                and Planificacion.Ano = PeriodosActivosAcademicos.Ano 
                and Planificacion.Periodo = PeriodosActivosAcademicos.Periodo 
				and Planificacion.Asignatura = '".substr($JSON->Codigo,1)."' 
				and Planificacion.Secuencia = '".substr($JSON->Codigo,0,1)."' 
                and Planificacion.Profesor = ".$_SESSION['Profesor']." 
				and Exists 
				   (Select 1
				      From UniMar.EstudiantesHistorico
				     Where UniMar.EstudiantesHistorico.Extension = UniMar.Extensiones.Extension
				       and UniMar.EstudiantesHistorico.Ano = UniMar.PeriodosActivosAcademicos.Ano
				       and UniMar.EstudiantesHistorico.Periodo = UniMar.PeriodosActivosAcademicos.Periodo
				       and UniMar.EstudiantesHistorico.NivelEstudios = UniMar.PeriodosActivosAcademicos.NivelEstudios
				       and UniMar.EstudiantesHistorico.Asignatura = UniMar.Planificacion.Asignatura
				       and UniMar.EstudiantesHistorico.Secuencia = UniMar.Planificacion.Secuencia
				       and UniMar.EstudiantesHistorico.Turno = UniMar.Planificacion.Turno
				       and UniMar.EstudiantesHistorico.Seccion = UniMar.Planificacion.Seccion)
			  Order by Decode(Planificacion.Turno, 'M',1,'T',2,'N',3),Planificacion.Seccion";
  $resultSet = odbc_do($db, $sql);
  if (odbc_num_rows($resultSet) > 0) 
    {$JSon['Codigo']=0;
     $i=0;
     $Secciones=array();
	 odbc_fetch_row($resultSet,0);
     while (odbc_fetch_row($resultSet))
     {$Seccion['SECCION']=odbc_result($resultSet,'SECCION');
	  $Seccion['DESCRIPCION']=utf8_encode(odbc_result($resultSet,'DESCRIPCION'));
      $Seccion['IMPRESA']=odbc_result($resultSet,'IMPRESA');
      $Secciones[$i++]=$Seccion;
	  }
     $JSon['Secciones']=$Secciones;} 
   else 
    {$JSon['Codigo']=1;
	 $JSon['Mensaje']='Profesor sin carga academica activa';}
  odbc_close($db);
  exit(json_encode($JSon)); 
	 ?> 
