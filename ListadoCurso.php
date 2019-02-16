<?php
  session_start();
  if (!isset($_SESSION['Profesor']))
    {$JSon['Codigo']=-1;
     $JSon['Mensaje']='No ha hecho LogIn';
	 exit(json_encode($JSon));}
  $Entrada=stripslashes($_GET['Parametros']);
  $JSON = json_decode($Entrada);
  include_once('../lib/Conectar.php'); 
	 $sql = "Select Estudiantes.Cedula, Estudiantes.Apellidos, Estudiantes.Nombres,
	                Decode(EstudiantesHistorico.Corte1, 50, 'AP',Decode(EstudiantesHistorico.Corte1,51,'RP', Decode(EstudiantesHistorico.Corte1,Null, Null, right('0'||EstudiantesHistorico.Corte1,2)))) as Corte1, 
	                Decode(EstudiantesHistorico.Corte2, 50, 'AP',Decode(EstudiantesHistorico.Corte2,51,'RP', Decode(EstudiantesHistorico.Corte2,Null, Null, right('0'||EstudiantesHistorico.Corte2,2))))  as Corte2, 
	                Decode(EstudiantesHistorico.Corte3, 50, 'AP',Decode(EstudiantesHistorico.Corte3,51,'RP', Decode(EstudiantesHistorico.Corte3,Null,Null, right('0'||EstudiantesHistorico.Corte3,2))))  as Corte3, 
	                Decode(EstudiantesHistorico.Corte4, 50, 'AP',Decode(EstudiantesHistorico.Corte4,51,'RP', Decode(EstudiantesHistorico.Corte4,Null,Null, right('0'||EstudiantesHistorico.Corte4,2)))) as Corte4, 
	                Decode(EstudiantesHistorico.Corte5, 50, 'AP',Decode(EstudiantesHistorico.Corte5,51,'RP', Decode(EstudiantesHistorico.Corte5,Null,Null, right('0'||EstudiantesHistorico.Corte5,2)))) as Corte5, 
	                Decode(EstudiantesHistorico.Nota, 50, 'AP',Decode(EstudiantesHistorico.Nota,51,'RP', Decode(EstudiantesHistorico.Nota,Null,Null, right('0'||EstudiantesHistorico.Nota,2)))) as Nota, 
	                Decode(EstudiantesHistorico.Reparacion, 50, 'AP',Decode(EstudiantesHistorico.Reparacion,51,'RP', Decode(EstudiantesHistorico.Reparacion,Null,Null, right('0'||EstudiantesHistorico.Reparacion,2)))) as Reparacion, 
                    Decode(InscritosReparacion.Renglon, Null, 0,1) as Repara
               From UniMar.EstudiantesHistorico, UniMar.Estudiantes,
                   (Select Facturas.Cedula, DetallesReparacion.Factura, 
				           DetallesReparacion.Renglon, DetallesReparacion.Asignatura
                     From  UniMarAdm.Facturas, UniMarAdm.FacturasDetalles,
					       UniMarAdm.DetallesReparacion
                    Where  FacturasDetalles.Factura = Facturas.Factura
                           and FacturasDetalles.Concepto = '07'
                           and FacturasDetalles.Periodo = '".substr($JSON->Periodo,4,1)."'
                           and FacturasDetalles.Ano = '".substr($JSON->Periodo,0,4)."'
                           and FacturasDetalles.NivelEstudios = '".substr($JSON->Periodo,5,1)."' 
                           and DetallesReparacion.Factura  = FacturasDetalles.Factura) as InscritosReparacion
              Where EstudiantesHistorico.NivelEstudios = '".substr($JSON->Periodo,5,1)."' 
				and EstudiantesHistorico.Ano = '".substr($JSON->Periodo,0,4)."' 
				and EstudiantesHistorico.Periodo = '".substr($JSON->Periodo,4,1)."' 
				and EstudiantesHistorico.Asignatura = '".substr($JSON->Codigo,1)."' 
				and EstudiantesHistorico.Secuencia = '".substr($JSON->Codigo,0,1)."' 
				and EstudiantesHistorico.Turno = '".substr($JSON->Seccion,1,1)."' 
				and EstudiantesHistorico.Seccion = '".substr($JSON->Seccion,2,2)."' 
				and Estudiantes.Cedula = EstudiantesHistorico.Cedula  
                and InscritosReparacion.Cedula (+) = Estudiantes.Cedula
                and InscritosReparacion.Asignatura (+) = EstudiantesHistorico.Asignatura
              Order by Estudiantes.Apellidos, Estudiantes.Nombres";
  $resultSetLista = odbc_do($db, $sql);
  if (odbc_num_rows($resultSetLista) > 0) 
    {$JSon['Codigo']=0;
     $i=0;
     $Lista=array();
	 odbc_fetch_row($resultSetLista, 0);
     while (odbc_fetch_row($resultSetLista))
     {$Estudiante['NUMERO']=$i+1;
	  $Estudiante['CEDULA']=odbc_result($resultSetLista, 'CEDULA');
	  $Estudiante['APELLIDOS']=utf8_encode(odbc_result($resultSetLista, 'APELLIDOS'));
	  $Estudiante['NOMBRES']=utf8_encode(odbc_result($resultSetLista, 'NOMBRES'));
	  $k=0;
	  $Notas= array();
	  while ($k < $JSON->NCortes)
	    {$Notas[$k]=odbc_result($resultSetLista, 'CORTE'.($k+1));
		 $k++;}
       	 array_push($Notas,odbc_result($resultSetLista, 'NOTA'));  
	  if ($JSON->Reparacion == true)
	    {$Estudiante['REPARACION']=odbc_result($resultSetLista, 'REPARACION');
		 array_push($Notas,odbc_result($resultSetLista, 'REPARACION'));
		 $Estudiante['REPARA']=odbc_result($resultSetLista, 'REPARA');}	
	  $Estudiante['NOTAS']=$Notas;
      $Lista[$i++]=$Estudiante;
	  }
     $JSon['Lista']=$Lista;} 
   else 
    {$JSon['Codigo']=1;
	 $JSon['Mensaje']='Profesor sin carga academica activa';}
  odbc_close($db);
  exit(json_encode($JSon)); 
	 ?> 
