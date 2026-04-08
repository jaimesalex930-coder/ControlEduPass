<?php
include "conexion.php";

if (isset($_POST['id_policia'])) {
    $id = $_POST['id_policia'];

    $sql = "DELETE FROM policias WHERE id_policia = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id]);

    header("Location: inicio.php");
    exit();
}
?>