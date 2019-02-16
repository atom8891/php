<?php 
  session_start();
  if (!isset($_SESSION['Profesor']))
    {$JSon['Codigo']=-1;
     $JSon['Mensaje']='No ha hecho LogIn';
	 exit(json_encode($JSon));}
  include_once('../conexion.php'); 
 $SQL="Select Clave, Descripcion, FechaFinal, Corte1, Corte2, Corte3, Corte4, Corte5,
              Corte1L, Corte2L, Corte3L, Corte4L, Corte5L, Repara, Corte1I, Corte2I, Corte3I, Corte4I, Corte5I
         From
             (Select Ano||Periodo||NivelEstudios as Clave, Descripcion, Max(Fecha) as FechaFinal, Corte1, Corte2, Corte3, Corte4, Corte5
                From
                    (Select ControlPeriodos.Extension, ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios,
                            decode(RNotas.FechaFinal,Null,ControlPeriodos.FechaFinal,
                            Case when ControlPeriodos.FechaFinal < RNotas.FechaFinal then RNotas.FechaFinal 
							     else ControlPeriodos.FechaFinal end) as Fecha,
                            ControlPeriodos.FechaFinal as FF, RNotas.FechaFinal, ControlPeriodos.Descripcion, Corte1, Corte2, Corte3, Corte4, Corte5
                       From Unimar.ControlPeriodos
                       Left Join
                                (Select ControlProcesos.NivelEstudios, ControlProcesos.Ano, ControlProcesos.Periodo,
                                        ControlProcesos.Proceso, ControlProcesos.FechaFinal
                                   From Unimar.ControlProcesos,
                                       (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                                          From Unimar.ControlPeriodos
                                         Where ControlPeriodos.NivelEstudios = '1'
                                           and CurDate() <= ControlPeriodos.FechaFinal) as Procesos
                                  Where ControlProcesos.NivelEstudios = Procesos.NivelEstudios
                                    and ControlProcesos.Ano = Procesos.Ano
                                    and ControlProcesos.Periodo = Procesos.Periodo
                                    and ControlProcesos.Proceso in ('07','08','09','10','11','15')) as RNotas
                              on ControlPeriodos.Ano = RNotas.Ano
                             and ControlPeriodos.Periodo = RNotas.Periodo
                             and ControlPeriodos.NivelEstudios = RNotas.NivelEstudios
                      Where ControlPeriodos.NivelEstudios = '1'
                        and   CurDate() <= ControlPeriodos.FechaFinal
                        and ControlPeriodos.Extension = '00') as Periodos
               Where Exists
                   (Select 1
                      From Unimar.Planificacion
                     Where Planificacion.Profesor = ".$_SESSION['Profesor']."
                       and Planificacion.Extension = Periodos.Extension
                       and Planificacion.NivelEstudios = Periodos.NivelEstudios
                       and Planificacion.Ano = Periodos.Ano
                       and Planificacion.Periodo = Periodos.Periodo
                       and Planificacion.Inscritos > 0)
               Group by  Ano||Periodo||NivelEstudios, Descripcion, Corte1, Corte2, Corte3, Corte4, Corte5) as PeriodosProfesor,
             (Select ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios as Fecha,
                     Case when (ControlProcesos.FechaInicio <= curdate() and curdate() <= ControlProcesos.FechaFinal) then 1
                          when (adddate(ControlProcesos.FechaFinal,0) < curdate()) then 3
                          else 0 End as Corte1L
                From UniMar.ControlPeriodos, Unimar.ControlProcesos,
                    (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                       From Unimar.ControlPeriodos
                      Where ControlPeriodos.NivelEstudios = '1'
                        and CurDate() <= ControlPeriodos.FechaFinal) as PP
               Where ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios = PP.Ano||PP.Periodo||PP.NivelEstudios
                 and ControlProcesos.NivelEstudios (+) =  ControlPeriodos.NivelEstudios
                 and ControlProcesos.Ano (+) = ControlPeriodos.Ano
                 and ControlProcesos.Periodo (+) = ControlPeriodos.Periodo
                 and ControlProcesos.Proceso (+) =  '07') C1,
             (Select ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios as Fecha,
                     Case when (ControlProcesos.FechaInicio <= curdate() and curdate() <= ControlProcesos.FechaFinal) then 1
                          when (adddate(ControlProcesos.FechaFinal,0) < curdate()) then 3
                          else 0 End as Corte2L
                From UniMar.ControlPeriodos, Unimar.ControlProcesos,
                    (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                       From Unimar.ControlPeriodos
                      Where ControlPeriodos.NivelEstudios = '1'
                        and CurDate() <= ControlPeriodos.FechaFinal) as PP
               Where ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios = PP.Ano||PP.Periodo||PP.NivelEstudios
                 and ControlProcesos.NivelEstudios (+) =  ControlPeriodos.NivelEstudios
                 and ControlProcesos.Ano (+) = ControlPeriodos.Ano
                 and ControlProcesos.Periodo (+) = ControlPeriodos.Periodo
                 and ControlProcesos.Proceso (+) = '08') C2,
             (Select ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios as Fecha,
                     Case when (ControlProcesos.FechaInicio <= curdate() and curdate() <= ControlProcesos.FechaFinal) then 1
                          when (adddate(ControlProcesos.FechaFinal,0) < curdate()) then 3
                          else 0 End as Corte3L
                From UniMar.ControlPeriodos, Unimar.ControlProcesos,
                    (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                       From Unimar.ControlPeriodos
                      Where ControlPeriodos.NivelEstudios = '1'
                        and CurDate() <= ControlPeriodos.FechaFinal) as PP
               Where ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios = PP.Ano||PP.Periodo||PP.NivelEstudios
                 and ControlProcesos.NivelEstudios (+) =  ControlPeriodos.NivelEstudios
                 and ControlProcesos.Ano (+) = ControlPeriodos.Ano
                 and ControlProcesos.Periodo (+) = ControlPeriodos.Periodo
                 and ControlProcesos.Proceso (+) = '09') C3,
             (Select ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios as Fecha,
                     Case when (ControlProcesos.FechaInicio <= curdate() and curdate() <= ControlProcesos.FechaFinal) then 1
                          when (adddate(ControlProcesos.FechaFinal,0) < curdate()) then 3
                          else 0 End as Corte4L
                From UniMar.ControlPeriodos, Unimar.ControlProcesos,
                    (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                       From Unimar.ControlPeriodos
                      Where ControlPeriodos.NivelEstudios = '1'
                        and CurDate() <= ControlPeriodos.FechaFinal) as PP
               Where ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios = PP.Ano||PP.Periodo||PP.NivelEstudios
                 and ControlProcesos.NivelEstudios (+) =  ControlPeriodos.NivelEstudios
                 and ControlProcesos.Ano (+) = ControlPeriodos.Ano
                 and ControlProcesos.Periodo (+) = ControlPeriodos.Periodo
                 and ControlProcesos.Proceso (+) = '10') C4,
             (Select ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios as Fecha,
                     Case when (ControlProcesos.FechaInicio <= curdate() and curdate() <= ControlProcesos.FechaFinal) then 1
                          when (adddate(ControlProcesos.FechaFinal,0) < curdate()) then 3
                          else 0 End as Corte5L
                From UniMar.ControlPeriodos, Unimar.ControlProcesos,
                    (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                       From Unimar.ControlPeriodos
                      Where ControlPeriodos.NivelEstudios = '1'
                        and CurDate() <= ControlPeriodos.FechaFinal) as PP
               Where ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios = PP.Ano||PP.Periodo||PP.NivelEstudios
                 and ControlProcesos.NivelEstudios (+) =  ControlPeriodos.NivelEstudios
                 and ControlProcesos.Ano (+) = ControlPeriodos.Ano
                 and ControlProcesos.Periodo (+) = ControlPeriodos.Periodo
                 and ControlProcesos.Proceso (+) = '11') C5,
             (Select ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios as Fecha,
                     Case when (ControlProcesos.FechaInicio <= curdate() and curdate() <= ControlProcesos.FechaFinal) then 1
                          when (adddate(ControlProcesos.FechaFinal,0) < curdate()) then 3
                          else 0 End as Repara
                From UniMar.ControlPeriodos, Unimar.ControlProcesos,
                    (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                       From Unimar.ControlPeriodos
                      Where ControlPeriodos.NivelEstudios = '1'
                        and CurDate() <= ControlPeriodos.FechaFinal) as PP
               Where ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios = PP.Ano||PP.Periodo||PP.NivelEstudios
                 and ControlProcesos.NivelEstudios (+) =  ControlPeriodos.NivelEstudios
                 and ControlProcesos.Ano (+) = ControlPeriodos.Ano
                 and ControlProcesos.Periodo (+) = ControlPeriodos.Periodo
                 and ControlProcesos.Proceso (+) = '15') RP,
             (Select ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios as Fecha,
                     Case when (ControlProcesos.FechaInicio <= curdate() and curdate() <= ControlProcesos.FechaFinal) then 1
                          when (adddate(ControlProcesos.FechaFinal,0) < curdate()) then 3
                          else 0 End as Corte1I
                From UniMar.ControlPeriodos, Unimar.ControlProcesos,
                    (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                       From Unimar.ControlPeriodos
                      Where ControlPeriodos.NivelEstudios = '1'
                        and CurDate() <= ControlPeriodos.FechaFinal) as PP
               Where ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios = PP.Ano||PP.Periodo||PP.NivelEstudios
                 and ControlProcesos.NivelEstudios (+) =  ControlPeriodos.NivelEstudios
                 and ControlProcesos.Ano (+) = ControlPeriodos.Ano
                 and ControlProcesos.Periodo (+) = ControlPeriodos.Periodo
                 and ControlProcesos.Proceso (+) = '17') C1I,
             (Select ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios as Fecha,
                     Case when (ControlProcesos.FechaInicio <= curdate() and curdate() <= ControlProcesos.FechaFinal) then 1
                          when (adddate(ControlProcesos.FechaFinal,0) < curdate()) then 3
                          else 0 End as Corte2I
                From UniMar.ControlPeriodos, Unimar.ControlProcesos,
                    (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                       From Unimar.ControlPeriodos
                      Where ControlPeriodos.NivelEstudios = '1'
                        and CurDate() <= ControlPeriodos.FechaFinal) as PP
               Where ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios = PP.Ano||PP.Periodo||PP.NivelEstudios
                 and ControlProcesos.NivelEstudios (+) =  ControlPeriodos.NivelEstudios
                 and ControlProcesos.Ano (+) = ControlPeriodos.Ano
                 and ControlProcesos.Periodo (+) = ControlPeriodos.Periodo
                 and ControlProcesos.Proceso (+) = '18') C2I,
             (Select ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios as Fecha,
                     Case when (ControlProcesos.FechaInicio <= curdate() and curdate() <= ControlProcesos.FechaFinal) then 1
                          when (adddate(ControlProcesos.FechaFinal,0) < curdate()) then 3
                          else 0 End as Corte3I
                From UniMar.ControlPeriodos, Unimar.ControlProcesos,
                    (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                       From Unimar.ControlPeriodos
                      Where ControlPeriodos.NivelEstudios = '1'
                        and CurDate() <= ControlPeriodos.FechaFinal) as PP
               Where ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios = PP.Ano||PP.Periodo||PP.NivelEstudios
                 and ControlProcesos.NivelEstudios (+) =  ControlPeriodos.NivelEstudios
                 and ControlProcesos.Ano (+) = ControlPeriodos.Ano
                 and ControlProcesos.Periodo (+) = ControlPeriodos.Periodo
                 and ControlProcesos.Proceso (+) = '19') C3I,
             (Select ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios as Fecha,
                     Case when (ControlProcesos.FechaInicio <= curdate() and curdate() <= ControlProcesos.FechaFinal) then 1
                          when (adddate(ControlProcesos.FechaFinal,0) < curdate()) then 3
                          else 0 End as Corte4I
                From UniMar.ControlPeriodos, Unimar.ControlProcesos,
                    (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                       From Unimar.ControlPeriodos
                      Where ControlPeriodos.NivelEstudios = '1'
                        and CurDate() <= ControlPeriodos.FechaFinal) as PP
               Where ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios = PP.Ano||PP.Periodo||PP.NivelEstudios
                 and ControlProcesos.NivelEstudios (+) =  ControlPeriodos.NivelEstudios
                 and ControlProcesos.Ano (+) = ControlPeriodos.Ano
                 and ControlProcesos.Periodo (+) = ControlPeriodos.Periodo
                 and ControlProcesos.Proceso (+) = '20') C4I,
             (Select ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios as Fecha,
                     Case when (ControlProcesos.FechaInicio <= curdate() and curdate() <= ControlProcesos.FechaFinal) then 1
                          when (adddate(ControlProcesos.FechaFinal,0) < curdate()) then 3
                          else 0 End as Corte5I
                From UniMar.ControlPeriodos, Unimar.ControlProcesos,
                    (Select ControlPeriodos.Ano, ControlPeriodos.Periodo, ControlPeriodos.NivelEstudios, ControlPeriodos.FechaFinal
                       From Unimar.ControlPeriodos
                      Where ControlPeriodos.NivelEstudios = '1'
                        and CurDate() <= ControlPeriodos.FechaFinal) as PP
               Where ControlPeriodos.Ano||ControlPeriodos.Periodo||ControlPeriodos.NivelEstudios = PP.Ano||PP.Periodo||PP.NivelEstudios
                 and ControlProcesos.NivelEstudios (+) =  ControlPeriodos.NivelEstudios
                 and ControlProcesos.Ano (+) = ControlPeriodos.Ano
                 and ControlProcesos.Periodo (+) = ControlPeriodos.Periodo
                 and ControlProcesos.Proceso (+) = '21') C5I
        where PeriodosProfesor.Clave = C1.Fecha
          and PeriodosProfesor.Clave = C2.Fecha
          and PeriodosProfesor.Clave = C3.Fecha
          and PeriodosProfesor.Clave = C4.Fecha
          and PeriodosProfesor.Clave = C5.Fecha
          and PeriodosProfesor.Clave = RP.Fecha
          and PeriodosProfesor.Clave = C1I.Fecha
          and PeriodosProfesor.Clave = C2I.Fecha
          and PeriodosProfesor.Clave = C3I.Fecha
          and PeriodosProfesor.Clave = C4I.Fecha
          and PeriodosProfesor.Clave = C5I.Fecha";		 
  $recordSet = odbc_do($db, $SQL);
  if (odbc_num_rows($recordSet) > 0) 
    {$JSon['Codigo']=0;
     $i=0;
	 odbc_fetch_row($recordSet,0);
     while (odbc_fetch_row($recordSet))
     {$Periodo['CLAVE']=odbc_result($recordSet, 'CLAVE');
      $Periodo['DESCRIPCION']=utf8_encode(odbc_result($recordSet, 'DESCRIPCION'));
	  $j=5;
	  $k=0;
	  $NCortes=0;
	  $Corte= array();	 
	  while ($j != 0)
	   {if ($k != 0)
	      {$Corte[]=odbc_result($recordSet, 'CORTE'.$j--);}
	     else 
	      {if (odbc_result($recordSet, 'CORTE'.$j) != 0)
		     {$NCortes=$j;
	          $Corte[]=odbc_result($recordSet, 'CORTE'.$j--);
		      $k=1;}
		     else
		     {$j--;}}}
	 $Periodo['CORTES']=array_reverse($Corte);
	 $Periodo['NCORTES']=$NCortes;
	 $k=0;
	  $StsLocal= array();
	 while ($k < $NCortes)
	  {$StsLocal[$k] = odbc_result($recordSet, 'CORTE'.($k+1).'L');
	   $k++;}
	 $k=0;
	  $StsInternet= array();
	 while ($k < $NCortes)
	  {$StsInternet[$k] = odbc_result($recordSet, 'CORTE'.($k+1).'I');
	   $k++;}
	 $Periodo['STATUSLOCAL']=$StsLocal;
	 $Periodo['STATUSINTENRNET']=$StsInternet;
	 $Periodo['STATUSCORTES']=($_SESSION['Origen'] == 'Internet')? $StsInternet :$StsLocal ;
	 $Periodo['STATUSREPARACION']=odbc_result($recordSet, 'REPARA');
     $Periodos[$i++]=$Periodo;
}
     $JSon['Periodo']=$Periodos;
	 $Profesor['CEDULA']=$_SESSION['Profesor'];
	 $Profesor['NOMBRE']=$_SESSION['Nombre'];
     $JSon['Profesor']=$Profesor;} 
   else 
    {$JSon['Codigo']=1;
	 $JSon['Mensaje']='Profesor sin carga academica activa';}
  odbc_close(); #cerrar la conexion
  exit(json_encode($JSon)); 
 ?>
