<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: inicio.php");
    exit();
}
include "conexion.php";

$nombre = $_POST['nombre'];
$placa = $_POST['placa'];
$turno = $_POST['turno'];

$sql = "INSERT INTO policias (nombre, placa, turno) VALUES (?,?,?)";
$stmt = $conexion->prepare($sql);

$stmt->execute([$nombre, $placa, $turno]);

header("Location: inicio.php?msg=policia_ok");
exit();
?>

