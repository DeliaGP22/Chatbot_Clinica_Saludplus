<?php
include("config/conexion.php");

$email = $_POST['email'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$medico = $_POST['medico'];

$stmt = $conn->prepare("INSERT INTO clientes (fecha, hora, medico) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fecha, $hora, $medico);
$stmt->execute();

header("Location: index.php");