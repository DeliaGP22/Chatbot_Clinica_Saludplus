<?php
session_start();

if(isset($_POST['login'])){
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    if($user == "admin" && $pass == "1234"){
        $_SESSION['admin'] = true;
        header("Location: admin.php");
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>

<link rel="stylesheet" href="styles.css">

</head>

<body>

<div class="box">
    <h2>🔐 Panel Admin</h2>

    <form method="POST">
        <input type="text" name="user" placeholder="Usuario" required>
        <input type="password" name="pass" placeholder="Contraseña" required>
        <button name="login">Entrar</button>
    </form>

    <?php if(isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
</div>

</body>
</html>