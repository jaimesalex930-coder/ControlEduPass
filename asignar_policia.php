<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: inicio.php");
    exit();
}

include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // validar que lleguen datos
    if (
        isset($_POST['codigo_visita']) &&
        isset($_POST['policia_id']) &&
        isset($_POST['estado'])
    ) {

        $codigo = $_POST['codigo_visita'];
        $policia = $_POST['policia_id'];
        $estado = $_POST['estado'];

        // actualizar policía y estado
        $sql = "UPDATE visitas 
                SET policia_id = ?, estado = ? 
                WHERE codigo_visita = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([$policia, $estado, $codigo]);

        header("Location: inicio.php?msg=asignado_ok");
        exit();

    } else {
        echo "Error: faltan datos del formulario";
    }

} else {
    echo "Acceso no permitido";
}
?>