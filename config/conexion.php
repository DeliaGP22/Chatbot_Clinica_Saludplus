<?php
$conn = new mysqli("localhost", "root", "", "clinica_citas", 3306);

if ($conn->connect_error) {
    die("Error de conexión");
}
?>