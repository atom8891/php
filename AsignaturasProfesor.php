<?php
  session_start();
  if (!isset($_SESSION['Profesor']))
    {$JSon['Codigo']=-1;
     $JSon['Mensaje']='No ha hecho LogIn';
	 exit(json_encode($JSon));}
  include_once('../lib/Conectar.php'); 
	
  $Data = stripslashes($_POST['Data']);
  $JSON = json_decode($Data);
  $sql = " Select * From (Select Distinct Asignaturas.Descripcion , Planificacion.Secuencia||Planificacion.Asignatura as Codigo, Asignaturas.Creditos,
	              Corte1, Corte2, Corte3, Corte4, Corte5, Reparacion, Cualitativa
             From UniMar.PeriodosActivosAcademicos, UniMar.Planificacion, UniMar.Asignaturas
 Where Planificacion.NivelEstudios = PeriodosActivosAcademicos.NivelEstudios
   and Planificacion.Ano = PeriodosActivosAcademicos.Ano
   and Planificacion.Periodo = PeriodosActivosAcademicos.Periodo
   and Planificacion.Extension = '00'
   and Planificacion.Profesor = ".$_SESSION['Profesor']." 
   and PeriodosActivosAcademicos.Ano||PeriodosActivosAcademicos.Periodo||PeriodosActivosAcademicos.NivelEstudios = '".$JSON->Periodo."'
   and Asignaturas.Asignatura = Planificacion.Asignatura
   and Asignaturas.Secuencia = Planificacion.Secuencia
   and Exists
 (Select 1
    From UniMar.EstudiantesHistorico
   Where EstudiantesHistorico.Extension = '00'
     and EstudiantesHistorico.Ano = UniMar.PeriodosActivosAcademicos.Ano
     and EstudiantesHistorico.Periodo = UniMar.PeriodosActivosAcademicos.Periodo
     and EstudiantesHistorico.NivelEstudios = UniMar.PeriodosActivosAcademicos.NivelEstudios
     and UniMar.EstudiantesHistorico.Asignatura = UniMar.Planificacion.Asignatura
     and UniMar.EstudiantesHistorico.Secuencia = UniMar.Planificacion.Secuencia
     and UniMar.EstudiantesHistorico.Turno = UniMar.Planificacion.Turno
     and UniMar.EstudiantesHistorico.Seccion = UniMar.Planificacion.Seccion) )
			  Order by 1";
	 $resultSetAsignaturas = odbc_do($db, $sql);
	 $Campos=odbc_fetch_array($resultSetAsignaturas);
  if (odbc_num_rows($resultSetAsignaturas) > 0) 
    {$JSon['Codigo']=0;
     $i=0;
     $Asignaturas=array();
	 odbc_fetch_row($resultSetAsignaturas, 0);
     while (odbc_fetch_row($resultSetAsignaturas))
     {$Asignatura['CODIGO']=odbc_result($resultSetAsignaturas, 'CODIGO');
	  $Asignatura['DESCRIPCION']=utf8_encode(odbc_result($resultSetAsignaturas, 'DESCRIPCION'));
	  $Asignatura['REPARACION']=odbc_result($resultSetAsignaturas, 'REPARACION');
	  $Asignatura['CUALITATIVA']=odbc_result($resultSetAsignaturas, 'CUALITATIVA');
	 $j=5;
	 $k=0;
	 $NCortes=0;
	 $Cortes= array();
	 $CortesR = array();
	 while ($j != 0)
	  {$TCorte='CORTE'.($j);
		  if ($k != 0)
	     {$Cortes[$j]=(is_null($Campos['CORTE'.$j--]))? null : $Campos['CORTE'.$j--];}
	    else 
		 {if (($Campos['CORTE'.$j] != 0) and (!is_null($Campos['CORTE'.$j])))
		    {$NCortes=$j;
		     $Cortes[$j]=$Campos['CORTE'.$j--];
			 $k=1;}
		   else
			{$j--;}
		 } $CortesR[$j]=$TCorte.'='.$Campos[$TCorte]; }
	 $Asignatura['CORTES']=$Cortes;
	 $Asignatura['CORTESR']=$CortesR;
	 $Asignatura['NCORTES']=$NCortes;
     $Asignaturas[$i++]=$Asignatura;}}
   else 
    {$JSon['Codigo']=1;
	 $JSon['Mensaje']='Profesor sin carga academica activa';}
  $JSon['Asignaturas']=$Asignaturas; 
  odbc_close($db);
  exit(json_encode($JSon)); 
	 ?> 
