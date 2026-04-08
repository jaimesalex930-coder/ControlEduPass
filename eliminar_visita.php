<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['admin'])) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $codigo = $_POST['codigo_visita'];

    $sql = "DELETE FROM visitas WHERE codigo_visita = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$codigo]);

    header("Location: inicio.php?estado_filtro=");
    exit();
}
