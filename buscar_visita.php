<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: inicio.php");
  exit();
}

include "conexion.php";

$visita = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $codigo = trim($_POST['codigo'] ?? '');

  echo "Código recibido: [" . $codigo . "]";
  exit();

  if (!empty($codigo)) {

    $sql = "SELECT v.*, p.nombre AS nombre_policia, p.placa, p.turno
            FROM visitas v
            LEFT JOIN policias p ON v.policia_id = p.id_policia
            WHERE v.codigo_visita = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([$codigo]);

    $visita = $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Resultado de búsqueda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="estilovis.css">
</head>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-7">

        <?php if (isset($visita) && $visita) { ?>

          <div class="card shadow-lg p-4">
            <h3 class="text-center mb-4 text-success">Visita encontrada</h3>

            <ul class="list-group list-group-flush">

              <li class="list-group-item"><b>Código:</b> <?= htmlspecialchars($visita['codigo_visita']) ?></li>
              <li class="list-group-item"><b>Nombre:</b> <?= htmlspecialchars($visita['nombre_completo']) ?></li>
              <li class="list-group-item"><b>Institución:</b> <?= htmlspecialchars($visita['institucion']) ?></li>
              <li class="list-group-item"><b>Correo:</b> <?= htmlspecialchars($visita['correo']) ?></li>

              <li class="list-group-item"><b>Teléfono:</b>
                <?= !empty($visita['telefono']) ? htmlspecialchars($visita['telefono']) : 'No registrado' ?>
              </li>

              <li class="list-group-item"><b>Motivo:</b> <?= htmlspecialchars($visita['motivo']) ?></li>
              <li class="list-group-item"><b>Tipo de visita:</b> <?= htmlspecialchars($visita['tipo_visita']) ?></li>

              <?php if (!empty($visita['numero_personas'])) { ?>
                <li class="list-group-item">
                  <b>Número de personas:</b> <?= htmlspecialchars($visita['numero_personas']) ?>
                </li>
              <?php } ?>

              <?php if (!empty($visita['laboratorio'])) { ?>
                <li class="list-group-item">
                  <b>Laboratorio:</b> <?= htmlspecialchars($visita['laboratorio']) ?>
                </li>
              <?php } ?>

              <li class="list-group-item"><b>Hora:</b> <?= htmlspecialchars($visita['hora']) ?></li>
              <li class="list-group-item"><b>Fecha:</b> <?= htmlspecialchars($visita['fecha']) ?></li>

              <li class="list-group-item">
                <b>Policía asignado:</b>
                <?= !empty($visita['nombre_policia']) ? htmlspecialchars($visita['nombre_policia']) : 'Sin asignar' ?>
              </li>

              <li class="list-group-item">
                <b>Placa:</b>
                <?= !empty($visita['placa']) ? htmlspecialchars($visita['placa']) : '---' ?>
              </li>

              <li class="list-group-item">
                <b>Turno:</b>
                <?= !empty($visita['turno']) ? htmlspecialchars($visita['turno']) : '---' ?>
              </li>

            </ul>

            <div class="text-center mt-4">
              <a href="inicio.php" class="btn btn-primary">Regresar al sistema</a>
            </div>
          </div>

        <?php } else { ?>

          <div class="card shadow-lg p-4 text-center">
            <h3 class="text-danger">Visita no encontrada</h3>
            <p>No existe ese código en el sistema</p>
            <a href="inicio.php" class="btn btn-danger">Volver</a>
          </div>

        <?php } ?>

      </div>
    </div>
  </div>
</body>

</html>