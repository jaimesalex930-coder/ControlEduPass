<?php
session_start();

if ($_POST) {

    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    if ($usuario === "admin" && $password === "1234") {

        $_SESSION['admin'] = true;

        header("Location: inicio.php");
        exit();

    } else {

        header("Location: inicio.php?error=1");
        exit();

    }

}
?>