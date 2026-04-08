<?php
session_start();
include "conexion.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Agendar Visita - U.T.S.E.M</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="estilo.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    window._bootstrapDuplicado = true;
  </script>
  <style>
    .img-uniforme {
      width: 300%;
      height: 100px;
      object-fit: cover;
    }

    #contacto .table {
      font-size: 1.1rem;
    }

    #contacto .table th,
    #contacto .table td {
      padding: 12px 16px;
      vertical-align: middle;
    }

    #contacto .table th {
      font-size: 1.15rem;
      text-align: center;
    }

    #contacto .badge {
      font-size: 0.95rem;
      padding: 6px 10px;
    }

    #contacto .card {
      max-width: 1100px;
      margin: auto;
    }
  </style>
</head>


<body>
  <header class="container py-3 text-center">
    <a href="https://utsem.edomex.gob.mx/" target="_blank" rel="noopener noreferrer">
      <img src="img/logo.png" alt="Logo UTSEM" class="logo img-fluid" />
    </a>
  </header>

  <div class="container-fluid my-4 px-3">
    <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">

      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="bienvenida-tab"
          data-bs-toggle="tab" data-bs-target="#bienvenida" type="button">
          <i class="bi bi-house"></i> Inicio
        </button>
      </li>


      <?php if (!isset($_SESSION['admin'])) { ?>

        <li class="nav-item" role="presentation">
          <button class="nav-link" id="agendar-tab"
            data-bs-toggle="tab" data-bs-target="#visita" type="button">
            <i class="bi bi-calendar-check"></i> Agendar Visita
          </button>
        </li>


        <li class="nav-item" role="presentation">
          <button class="nav-link" id="login-tab"
            data-bs-toggle="tab" data-bs-target="#login" type="button">
            <i class="bi bi-person-lock"></i> Administrador
          </button>
        </li>

      <?php } ?>


      <?php if (isset($_SESSION['admin'])) { ?>
        <li class="nav-item" role="presentation">
          <button class="nav-link"
            data-bs-toggle="tab" data-bs-target="#contacto" type="button">
            <i class="bi bi-clipboard-check"></i> Administrar Visita
          </button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link"
            data-bs-toggle="tab" data-bs-target="#registrar" type="button">
            <i class="bi bi-shield-check"></i> Asignar Policía
          </button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link"
            data-bs-toggle="tab" data-bs-target="#policias" type="button">
            <i class="bi bi-shield-lock"></i> Policías
          </button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link"
            data-bs-toggle="tab" data-bs-target="#eliminarPolicia" type="button">
            <i class="bi bi-person-x"></i> Eliminar Policías
          </button>
        </li>

        <li class="nav-item">
          <a href="logout.php" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
          </a>
        </li>

      <?php } ?>

    </ul>
  </div>

  <div class="container my-4">
    <?php
    if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'exito') {
      $codigo = htmlspecialchars($_GET['codigo'] ?? '');
      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
      echo '<strong>¡Visita agendada exitosamente!</strong><br>';
      echo 'Su código de visita es: <strong>' . $codigo . '</strong>';
      echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
      echo '</div>';
    }
    ?>

    <div class="tab-content" id="myTabContent">

      <!-- PESTAÑA ELIMINAR POLICIA -->
      <div class="tab-pane fade" id="eliminarPolicia" role="tabpanel">
        <div class="card shadow transparent-card centered-card">
          <div class="card-body">
            <h5 class="card-title text-center">Eliminar Policías</h5>

            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Placa</th>
                    <th>Turno</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                  $policias = $conexion->query("SELECT * FROM policias");

                  foreach ($policias as $p) {
                    echo "<tr>
                <td>{$p['id_policia']}</td>
                <td>{$p['nombre']}</td>
                <td>{$p['placa']}</td>
                <td>{$p['turno']}</td>
                <td>
                  <form action='eliminar_policia.php' method='POST'
                    onsubmit=\"return confirm('¿Seguro que deseas eliminar este policía?');\">
                    
                    <input type='hidden' name='id_policia' value='{$p['id_policia']}'>
                    
                    <button class='btn btn-danger btn-sm'>
                      <i class='bi bi-trash'></i> Eliminar
                    </button>
                  </form>
                </td>
              </tr>";
                  }
                  ?>

                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
     
      <!-- PESTAÑA LOGIN -->
      <div class="tab-pane fade" id="login" role="tabpanel">
        <div class="card shadow transparent-card centered-card">
          <div class="card-body">
            <h5 class="text-center">Inicio de Sesión</h5>

            <form action="login_admin.php" method="POST">
              <div class="mb-3">
                <label>Usuario</label>
                <input type="text" name="usuario" class="form-control" required>
              </div>

              <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-box-arrow-in-right"></i> Entrar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- PESTAÑA DE BIENVENIDA -->
      <div class="tab-pane fade show active" id="bienvenida" role="tabpanel">
        <div class="card shadow transparent-card centered-card text-center p-4">
          <h4>Bienvenido a U.T.S.E.M</h4>
          <p>Selecciona una opción en el menú de arriba para continuar</p>
          <p>Para más información sobre nosotros da clic en la imagen de la UTSEM</p>
          <p>Universidad Tecnológica del Sur del Estado de México</p>
          <div class="row row-cols-2 row-cols-md-4 g-3 mt-4">
            <div class="col">
              <img src="img/logo 1.jpg" class="img-fluid rounded shadow img-uniforme" alt="Imagen 1" />
            </div>
            <div class="col">
              <img src="img/UTSEM1.jpg.jpeg" class="img-fluid rounded shadow img-uniforme" alt="Imagen 2" />
            </div>
            <div class="col">
              <img src="img/UTSEM2.jpg.jpeg" class="img-fluid rounded shadow img-uniforme" alt="Imagen 3" />
            </div>
            <div class="col">
              <img src="img/UTSEM3.jpg.jpeg" class="img-fluid rounded shadow img-uniforme" alt="Imagen 4" />
            </div>
          </div>
        </div>
      </div>

      <!-- PESTAÑA AGENDAR VISITA -->
      <div class="tab-pane fade" id="visita" role="tabpanel">
        <div class="card shadow transparent-card centered-card">
          <div class="card-body">
            <h5 class="card-title text-center">Agendar Visita</h5>
            <form action="agendar_visita.php" method="POST">
              <div class="mb-3">
                <label class="form-label">Nombre completo</label>
                <input type="text" class="form-control" name="nombre" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Institución de procedencia</label>
                <input type="text" class="form-control" name="institucion" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="correo" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Numero Telefonico</label>
                <input
                  type="tel" class="form-control" name="numero" pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Motivo de la visita</label>
                <textarea class="form-control" rows="2" name="motivo" required></textarea>
              </div>

              <select class="form-select" name="tipo_visita" id="tipo_visita" required onchange="mostrarCamposExtra()">
                <option value="">Seleccione una opción</option>
                <option value="Individual">Visita Individual</option>
                <option value="Grupo">Por Grupo</option>
                <option value="Laboratorio">Uso de Laboratorios</option>
              </select>
              <div class="mb-3" id="campo_personas" style="display:none;">
                <label class="form-label">Número de personas</label>
                <input type="number" class="form-control" name="numero_personas" min="1">
              </div>

              <div class="mb-3" id="campo_laboratorio" style="display:none;">
                <label class="form-label">Laboratorio solicitado</label>
                <select class="form-select" name="laboratorio">
                  <option value="">Seleccione un laboratorio</option>
                  <option value="Laboratorio de Redes">Laboratorio de Redes</option>
                  <option value="Laboratorio V de Informatica">Laboratorio V de Informatica</option>
                  <option value="Laboratorio VI de Informatica">Laboratorio VI de Informatica</option>
                  <option value="Laboratorio VII de informatica">Laboratorio VIII de Informatica</option>
                  <option value="Talleres de Mecatronica">Talleres de Mecatronica </option>
                  <option value="Subtalleres de Mecatronica">-> Taller de Informatica</option>
                  <option value="Subtalleres de Mecatronica">-> Taller de Metrologia</option>

                  <option value="Laboratorio de Enfermeria">Laboratorio de Enfermeria</option>
                  <option value="Laboratorio de Procesos">Laboratorio de Procesos</option>

                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Hora de la visita</label>
                <input type="time" class="form-control" name="hora" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Fecha de la visita</label>
                <input type="date" class="form-control" name="fecha" id="fecha" />
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-primary">Agendar Visita</button>
              </div>
            </form>
          </div>
        </div>
      </div>



      <!-- PESTAÑA ADMINISTRAR VISITA -->
      <div class="tab-pane fade" id="contacto" role="tabpanel">
        <div class="card shadow transparent-card centered-card">
          <div class="card-body">
            <h5 class="card-title text-center mb-4">Administrar Visitas</h5>

            <!-- FILTRO -->
            <form method="GET" class="mb-3 text-center">
              <label class="form-label">Filtrar por estado:</label>
              <select name="estado_filtro" class="form-select w-50 mx-auto" onchange="this.form.submit()">
                <option value="">Todas</option>
                <option value="Iniciada" <?= (($_GET['estado_filtro'] ?? '') == 'Iniciada') ? 'selected' : '' ?>>Iniciada</option>
                <option value="En proceso" <?= (($_GET['estado_filtro'] ?? '') == 'En proceso') ? 'selected' : '' ?>>En proceso</option>
                <option value="Terminada" <?= (($_GET['estado_filtro'] ?? '') == 'Terminada') ? 'selected' : '' ?>>Terminada</option>
              </select>
            </form>

            <!-- TABLA -->
            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Institución</th>
                    <th>Telefono</th>
                    <th>Laboratorio</th>
                    <th>Policía</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $estadoFiltro = $_GET['estado_filtro'] ?? '';
                  if ($estadoFiltro != "") {
                    $sql = "SELECT v.codigo_visita,
                             v.nombre_completo,
                             v.institucion,
                             v.telefono,
                             v.laboratorio,
                             v.estado,
                             v.fecha,
                             p.nombre AS policia
                      FROM visitas v
                      LEFT JOIN policias p ON v.policia_id = p.id_policia
                      WHERE v.estado = ?
                      ORDER BY v.fecha DESC";

                    $stmt = $conexion->prepare($sql);
                    $stmt->execute([$estadoFiltro]);
                  } else {

                    $sql = "SELECT v.codigo_visita,
                             v.nombre_completo,
                             v.institucion,
                             v.telefono,
                             v.laboratorio,
                             v.estado,
                             v.fecha,
                             p.nombre AS policia
                      FROM visitas v
                      LEFT JOIN policias p ON v.policia_id = p.id_policia
                      ORDER BY v.fecha DESC";

                    $stmt = $conexion->prepare($sql);
                    $stmt->execute();
                  }

                  $visitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  if ($visitas) {
                    foreach ($visitas as $v) {
                      $color = "secondary";
                      if ($v['estado'] == "Pendiente") $color = "warning";
                      if ($v['estado'] == "Iniciada") $color = "primary";
                      if ($v['estado'] == "En proceso") $color = "info";
                      if ($v['estado'] == "Terminada") $color = "success";

                      echo "<tr>
                     <td>{$v['codigo_visita']}</td>
                     <td>{$v['nombre_completo']}</td>
                     <td>{$v['institucion']}</td>
                     <td>{$v['telefono']}</td>
                     <td>" . ($v['laboratorio'] ?? 'N/A') . "</td>
                     <td>" . ($v['policia'] ?? 'Sin asignar') . "</td>
                     <td><span class='badge bg-$color'>{$v['estado']}</span></td>
                     <td>{$v['fecha']}</td>

                     <td>";
                      if ($v['estado'] == "Terminada") {
                        echo "<form action='eliminar_visita.php' method='POST'
                        onsubmit=\"return confirm('¿Seguro que deseas eliminar esta visita?');\">
                        <input type='hidden' name='codigo_visita' value='{$v['codigo_visita']}'>
                        <button class='btn btn-danger btn-sm'>
                        <i class='bi bi-trash'></i> Eliminar
                        </button>
                       </form>";
                      } else {
                        echo "<span class='text-muted'>Bloqueado</span>";
                      }
                      echo "</td>
                      </tr>";
                    }
                  } else {
                    echo "<tr>
                      <td colspan='7' class='text-center'>
                      No hay visitas en este estado
                      </td>
                    </tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>


      <!-- PESTAÑA DE ASIGNAR POLICA A VISITA -->
      <div class="tab-pane fade" id="registrar" role="tabpanel">
        <div class="card shadow transparent-card centered-card">
          <div class="card-body">
            <h5 class="card-title text-center mb-4">Asignar Policía a Visita</h5>
            <form action="asignar_policia.php" method="POST">
              <div class="mb-3">
                <label class="form-label">Seleccionar Visita</label>
                <select class="form-select" name="codigo_visita" required>
                  <option value="">Seleccione una visita...</option>
                  <?php
                  $visitas = $conexion->query("SELECT codigo_visita, nombre_completo FROM visitas ORDER BY fecha DESC");
                  foreach ($visitas as $v) {
                    echo "<option value='{$v['codigo_visita']}'>
                  {$v['codigo_visita']} - {$v['nombre_completo']}
                  </option>";
                  }
                  ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Seleccionar Policía</label>
                <select class="form-select" name="policia_id" required>
                  <option value="">Seleccione un policía...</option>
                  <?php
                  $policias = $conexion->query("SELECT id_policia, nombre FROM policias");
                  foreach ($policias as $p) {
                    echo "<option value='{$p['id_policia']}'>{$p['nombre']}</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Estado de la Visita</label>
                <select class="form-select" name="estado" required>
                  <option value="Pendiente">Pendiente</option>
                  <option value="Iniciada">Iniciada</option>
                  <option value="En proceso">En proceso</option>
                  <option value="Terminada">Terminada</option>
                </select>
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-success">
                  Asignar Policía
                </button>
              </div>

            </form>

          </div>
        </div>
      </div>


      <!-- PESTAÑA VISITAS ASIGNADAS (SE AGREGA DINÁMICAMENTE) -->
      <div class="tab-pane fade" id="visitasAsignadas" role="tabpanel">
        <div class="card shadow transparent-card p-4">
          <h4 class="text-center mb-4">Visitas Asignadas</h4>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID Visita</th>
                  <th>Nombre</th>
                  <th>Institución</th>
                  <th>Policía Asignado</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "SELECT codigo_visita, nombre_completo, institucion 
                FROM visitas
                ORDER BY fecha DESC";
                $stmt = $conexion->prepare($sql);
                $stmt->execute();
                $visitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($visitas) > 0) {
                  foreach ($visitas as $v) {
                    echo "<tr>";
                    echo "<td>{$v['codigo_visita']}</td>";
                    echo "<td>{$v['nombre_completo']}</td>";
                    echo "<td>{$v['institucion']}</td>";
                    echo "<td>Sin asignar</td>";
                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='4' class='text-center'>No hay visitas registradas</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>


      <!-- PESTAÑA ADMINISTRACIÓN GENERAL (SE AGREGA DINÁMICAMENTE) -->
      <div class="tab-pane fade" id="adminGeneral" role="tabpanel">
        <div class="card shadow transparent-card p-4 centered-card">
          <h4 class="text-center mb-4">Administración General</h4>

          <!-- Login para Admin General -->
          <div id="loginAdminGeneralContainer">
            <p class="text-center">Inicie sesión para administrar registros</p>
            <form id="loginAdminGeneralForm">
              <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" class="form-control" id="adminUser" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="adminPass" required />
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary">Entrar</button>
              </div>
            </form>
          </div>



          <!-- Formulario para eliminar registros, oculto hasta login -->
          <div id="adminGeneralForm" style="display:none;">
            <p class="text-center">Eliminar registros de visitas o policías.</p>
            <form id="eliminarRegistroForm">
              <div class="mb-3">
                <label class="form-label">Tipo de Registro</label>
                <select class="form-select" id="tipoRegistroEliminar" required>
                  <option value="">Seleccione...</option>
                  <option value="Visita">Visita</option>
                  <option value="Policía">Policía</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">ID o Nombre</label>
                <input type="text" class="form-control" id="registroIdNombre"
                  placeholder="Ingrese ID o Nombre a eliminar" required />
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-danger">Eliminar Registro</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- PESTAÑA POLICÍAS -->
      <div class="tab-pane fade" id="policias" role="tabpanel">
        <div class="card shadow transparent-card centered-card">
          <div class="card-body">
            <h5 class="card-title text-center">Registrar Policías</h5>
            <form action="registrar_policia.php" method="POST" class="mb-4">
              <div class="mb-3">
                <label class="form-label">Nombre del Policía</label>
                <input type="text" class="form-control" name="nombre" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Número de Placa</label>
                <input type="text" class="form-control" name="placa" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Turno</label>
                <select class="form-select" name="turno" required>
                  <option value="Mañana">Mañana</option>
                  <option value="Tarde">Tarde</option>
                  <option value="Noche">Noche</option>
                </select>
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-success">Registrar Policía</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>


    <footer class="text-center mt-4">
      <small class="text-muted">U.T.S.M | Para más información: utsem@gmail.com</small>
    </footer>
  </div>

  <script>
    setTimeout(function() {
      let alerta = document.querySelector('.alert');
      if (alerta) {
        let bsAlert = new bootstrap.Alert(alerta);
        bsAlert.close();
      }
    }, 10000);
  </script>

  <script src="java.js"></script>
  <script>
    function mostrarCamposExtra() {
      let tipo = document.getElementById("tipo_visita").value;
      let personas = document.getElementById("campo_personas");
      let laboratorio = document.getElementById("campo_laboratorio");

      personas.style.display = "none";
      laboratorio.style.display = "none";

      if (tipo === "Grupo") {
        personas.style.display = "block";
      }

      if (tipo === "Laboratorio") {
        laboratorio.style.display = "block";
        personas.style.display = "block";
      }
    }
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const params = new URLSearchParams(window.location.search);
      if (params.has("estado_filtro")) {
        const tab = document.querySelector('button[data-bs-target="#contacto"]');
        if (tab) {
          const bsTab = new bootstrap.Tab(tab);
          bsTab.show();
        }
      }
      const tabs = document.querySelectorAll('button[data-bs-toggle="tab"]');
      tabs.forEach(tab => {
        tab.addEventListener("shown.bs.tab", function() {
          localStorage.setItem("tabActiva", this.getAttribute("data-bs-target"));
        });
      });
      const tabActiva = localStorage.getItem("tabActiva");
      if (tabActiva) {
        const tab = document.querySelector('button[data-bs-target="' + tabActiva + '"]');
        if (tab) {
          const bsTab = new bootstrap.Tab(tab);
          bsTab.show();
        }
      }
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let hoy = new Date();
      let yyyy = hoy.getFullYear();
      let mm = String(hoy.getMonth() + 1).padStart(2, '0');
      let dd = String(hoy.getDate()).padStart(2, '0');

      let fechaHoy = yyyy + "-" + mm + "-" + dd;

      document.getElementById("fecha").setAttribute("min", fechaHoy);
    });
  </script>
</body>

</html>