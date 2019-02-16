<!-- 
  Creado en Simarca con muchísimo amor para todo quien haga vida en la Unimar.
  Si estás aquí, es porque te gusta conocer cómo trabaja algo por abajo, si encontraste
  algún fallo, notíficalo a: albertojseg@unimar.edu.ve
  -->
<!DOCTYPE HTML>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8" />
    <title>Universidad de Margarita</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="alert alert-info msg-beta">
      <strong>¡Épale estudiante!</strong> este sitio aún está en desarollo, podría tener falta de contenidos y fallos, si quieres 
      volver a la versión anterior, pincha <a href="">aquí</a>.
    </div>
    <nav class="navbar navbar-default">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-resource-unimar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
        </button>
        <a class="navbar-brand">
          <div class="visible-lg visible-md visible-sm">
            <img class="img-responsive centered" src="../images/logo_over.png">
          </div>
          <div class="visible-xs">
            <img class="img-responsive centered" src="../images/logo_screen_small.png">
          </div>
        </a>
      </div>
      <div class="collapse navbar-collapse" id="navbar-resource-unimar">
        <ul class="nav navbar-nav">
          <li><a href=""><i class="fa fa-university"></i> La Universidad <span class="caret"></span></a></li>
          <li><a href=""><i class="fa fa-user"></i> Pregado</a></li>
          <li><a href=""><i class="fa fa-graduation-cap"></i> Postgrado</a></li>
          <li><a href=""><i class="fa fa-gavel"></i> Secretaría general</a></li>
          <li><a href=""><i class="fa fa-external-link"></i> Estudiante en línea</a></li>
          <li><a href=""><i class="fa fa-envelope"></i> Correo institucional</a></li>
        </ul>
      </div>
    </nav>
    <div class="container firt-container">
      <header>
        <div id="unimar-photo-carousel" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#unimar-photo-carousel" data-slide-to="0" class="active"></li>
            <li data-target="#unimar-photo-carousel" data-slide-to="1"></li>
          </ol>
          <div class="carousel-inner">
            <div class="item active">
              <img src="../images/carousel/c_1.jpg" width="100%" height="100%">
            </div>
            <div class="item">
              <img src="../images/carousel/c_2.jpg" width="100%">
            </div>
          </div>
        </div>
      </header>
      <section>
     
            <div class=" col-md-12">
              <div class="panel">
                <div id="exTab1" >
                  <ul  class="nav nav-pills">
                    <li class="active">
                      <a  href="#1a" data-toggle="tab"> <i class="fa fa-user-o" aria-hidden="true"></i>  Modificar</a>
                    </li>
                    <li><a href="#2a" data-toggle="tab"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>  Documentos Académicos</a>
                    </li>
                    <li><a href="#3a" data-toggle="tab"><i class="fa fa-folder-o" aria-hidden="true"></i>  Otras Planillas</a>
                    </li> 
                    <li><a href="#4a" data-toggle="tab"></a>
                    </li>
                  </ul>
                  <div class="tab-content clearfix">
                    <div class="tab-pane active" id="1a">
                    <h4><a href="../php/PerfilProfesor.php"> <i class="fa fa-user" aria-hidden="true"></i> Solicitud de Nueva Contraseña</a></h4>
                    <h4><a href="../php/ActualizarNotas.php"> <i class="fa fa-briefcase" aria-hidden="true"></i> Registrar Notas</a></h4>
                    </div>
                    <div class="tab-pane" id="2a">
                    <h4><a href="../php/SeccionesAsignaturasProfesor.php"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Carga Académica</a></h4>
                     <label for="sel1">Selececcione Asignatura:<select class="form-control  col-md-8" id="sel1"></label>
                     <option>1</option>
                     <option>2</option>
                     <option>3</option>
                    <option>4</option>
                 </select>
                 <div><label for="sel1">Selecccione Sección:<select class="form-control  col-md-2" id="sel1"></label>
                     <option>1</option>
                     <option>2</option>
                     <option>3</option>
                    <option>4</option>
                 </select></div>
                    <h4><a href="../php/PeriodosProfesor.php"></a><input type="radio" name="optradio"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Evaluación Continua</h4>
                    <h4><a href="../php/ListadoCurso.php"></a><input type="radio" name="optradio"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Control de Asistencia</h4>
                    </div>
                    <div class="tab-pane" id="3a">
                    <h4><a href="../php/ListadoCurso.php"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Planilla Porcentaje de Evaluación</h4></a>
                    <h4><a href="../php/ListadoCurso.php"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Planilla de Plan de Evaluación</h4></a>
                    <h4><a href="../php/ListadoCurso.php"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Planilla de Cronograma de Evaluación</h4></a>
                    <h4><a href="../php/ListadoCurso.php"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Planilla de Materia Vista</h4></a>
                    </div>
                    <div class="tab-pane" id="4a">
                      <h3>.a.a.</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-2">
            
              </div>
            </div>
          </div>
        </div>
      </section>
      <!--<section class="institutional_information">
        <img src="resource/images/c_1.jpg" width="100%">
        </section>-->
      <footer>
        <div class="row">
          <div class="col-xs-3">
            <img class="img-responsive" src="resource/images/logo_over.png">
          </div>
          <div class="col-xs-9">
            ...
          </div>
        </div>
      </footer>
    </div>
  </body>
</html>