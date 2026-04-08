<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "conexion.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Error: Método no permitido");
}



$nombre       = trim($_POST['nombre'] ?? '');
$institucion  = trim($_POST['institucion'] ?? '');
$correo       = trim($_POST['correo'] ?? '');
$telefono     = trim($_POST['numero'] ?? '');
$motivo       = trim($_POST['motivo'] ?? '');
$hora         = trim($_POST['hora'] ?? '');
$fecha        = trim($_POST['fecha'] ?? '');

$tipo_visita  = $_POST['tipo_visita'] ?? null;

$numero_personas = (!empty($_POST['numero_personas'])) ? (int)$_POST['numero_personas'] : null;
$laboratorio     = (!empty($_POST['laboratorio'])) ? trim($_POST['laboratorio']) : null;


if (
    empty($nombre) ||
    empty($institucion) ||
    empty($correo) ||
    empty($telefono) ||
    empty($motivo) ||
    empty($hora) ||
    empty($fecha) ||
    empty($tipo_visita)
) {
    die("Error: Campos incompletos");
}

/* Validar fecha no pasada */
$hoy = date("Y-m-d");
if ($fecha < $hoy) {
    die("Error: No se pueden agendar visitas en fechas pasadas.");
}

/* Validar teléfono (10 dígitos) */

if (!preg_match('/^[0-9]{10}$/', $telefono)) {
    die("Error: El teléfono debe tener 10 dígitos.");
}

try {

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $year = date('Y');

    $sql = "SELECT MAX(CAST(SUBSTRING(codigo_visita, -4) AS UNSIGNED)) AS ultimo
            FROM visitas
            WHERE codigo_visita LIKE ?";

    $stmt = $conexion->prepare($sql);
    $stmt->execute(["UTSM-$year-%"]);

    $ultimo = $stmt->fetch(PDO::FETCH_ASSOC)['ultimo'] ?? 0;
    $nuevo = $ultimo + 1;

    $codigo_visita = sprintf("UTSM-%d-%04d", $year, $nuevo);

 

    $sql = "INSERT INTO visitas
    (codigo_visita,nombre_completo,institucion,correo,telefono,motivo,tipo_visita,
    numero_personas,laboratorio,hora,fecha,estado)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        $codigo_visita,
        $nombre,
        $institucion,
        $correo,
        $telefono,
        $motivo,
        $tipo_visita,
        $numero_personas,
        $laboratorio,
        $hora,
        $fecha,
        "Pendiente"
    ]);

    /* ============================
       ENVÍO DE CORREO
    ============================ */

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'fernando192macias@gmail.com';
        $mail->Password = 'ialxfgjwjjspzznz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $mail->setFrom(
            'fernando192macias@gmail.com',
            'Universidad Tecnológica del Sur del Estado de México'
        );

        $mail->addAddress($correo, $nombre);
        $mail->addAddress('fernando192macias@gmail.com');
        $mail->addReplyTo('fernando192macias@gmail.com', 'UTSEM');

        $mail->isHTML(true);
        $mail->Subject = "Confirmación de Visita UTSEM - $codigo_visita";

        $mail->AltBody =
            "UTSEM\n" .
            "Codigo de visita: $codigo_visita\n" .
            "Fecha: $fecha\n" .
            "Hora: $hora\n" .
            "Teléfono: $telefono";

        $mail->Body = "
        <div style='font-family:Arial;background:#f4f6f8;padding:20px'>
        <div style='max-width:600px;background:white;margin:auto;border-radius:10px;padding:20px;border:1px solid #ddd'>

        <h2 style='color:#0d6efd;text-align:center'>
        Universidad Tecnológica del Sur del Estado de México
        </h2>

        <p>Hola <b>$nombre</b>,</p>
        <p>Su visita ha sido registrada correctamente.</p>

        <div style='background:#e9f2ff;padding:15px;border-radius:8px;margin:15px 0;text-align:center'>
        <p style='margin:0'>Código de visita</p>
        <h2 style='color:#0d6efd;margin:0'>$codigo_visita</h2>
        </div>

        <table style='width:100%;font-size:14px'>
        <tr><td><b>Fecha:</b></td><td>$fecha</td></tr>
        <tr><td><b>Hora:</b></td><td>$hora</td></tr>
        <tr><td><b>Teléfono:</b></td><td>$telefono</td></tr>
        <tr><td><b>Institución:</b></td><td>$institucion</td></tr>
        </table>

        <br>

        <div style='text-align:center'>
        <a href='https://utsem.edomex.gob.mx/'
        style='background:#0d6efd;color:white;padding:12px 25px;text-decoration:none;border-radius:6px;font-weight:bold'>
        Visitar sitio oficial
        </a>
        </div>

        <p style='font-size:12px;color:#777;text-align:center;margin-top:20px'>
        Este correo fue enviado automáticamente por el sistema UTSEM.
        </p>

        </div>
        </div>";

        $mail->send();

    } catch (Exception $e) {
        echo "Error enviando correo: " . $mail->ErrorInfo;
    }

    header("Location: inicio.php?mensaje=exito&codigo=" . urlencode($codigo_visita));
    exit();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}