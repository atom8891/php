<?php
 header('Content-Type: text/xml; charset=ISO-8859-1');
?>
<?php require_once('unimarconn2.php'); ?>
<?php
	mysql_select_db($database_conn, $conexion);
	
	$cedula = $_POST['cedula'];
	//$asignatura = $_POST['asignatura'];
	list($asignatura, $descrip, $secuencia) = split('/', $_POST['asignatura']);
		$var_periodo = $_POST['Periodo'];
		$var_ano = $_POST['Ano'];
		$var_nivel = $_POST['NivelEstudios'];
	
	$consulta = "SELECT
				Planificacion.Profesor,
				Planificacion.Ano,
				Planificacion.Periodo,
				Planificacion.Asignatura,
				Planificacion.Secuencia,
				Planificacion.Turno,
				Planificacion.Seccion,
				Asignaturas.Descripcion
			FROM
				Planificacion
			Inner Join Asignaturas ON Asignaturas.Asignatura = Planificacion.Asignatura
			Where Planificacion.Extension = '00'
   and Planificacion.NivelEstudios = '$var_nivel'
   and Planificacion.Ano = '$var_ano'
   and Planificacion.Periodo = '$var_periodo'
   and Planificacion.Profesor = $cedula
   and Planificacion.Asignatura = '$asignatura'";
	
	$query_Recordset1 = $consulta;
	$Recordset1 = mysql_query($query_Recordset1, $conexion) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<select name="selsecc" id="selsecc" class="Default">
  <option value="">- Seleccione la Sección -</option>
  <?php if($totalRows_Recordset1 > 0){?>
  <?php do{ ?>
  <option value="<?php echo $row_Recordset1['Turno'].'-'.$row_Recordset1['Seccion'];?>"><?php echo $row_Recordset1['Turno'].'-'.$row_Recordset1['Seccion'];?></option>
  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));?>
  <?php }?>
</select>
</body>
</html>
<?php
	mysql_free_result($Recordset1);
?>