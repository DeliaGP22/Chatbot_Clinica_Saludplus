<?php
session_start();
include("config/conexion.php");
require_once(__DIR__ . "/email.php");

// RESET
if(isset($_POST['reset'])){
    session_destroy();
    header("Location:index.php");
    exit();
}

// VOLVER
if(isset($_POST['volver'])){
    switch($_SESSION['paso']){
        case 'email': $_SESSION['paso']='nombre'; break;
        case 'medico': $_SESSION['paso']='email'; break;
        case 'fecha': $_SESSION['paso']='medico'; break;
        case 'hora': $_SESSION['paso']='fecha'; break;
    }
}

$_SESSION['paso'] = $_SESSION['paso'] ?? 'inicio';

// HORAS DISPONIBLES
function horasDisponibles($conn,$fecha,$medico){
    $horas=["09:00","10:00","11:00","12:00","13:00","16:00","17:00"];

    $stmt=$conn->prepare("SELECT hora FROM clientes WHERE fecha=? AND medico=?");
    $stmt->bind_param("ss",$fecha,$medico);
    $stmt->execute();
    $res=$stmt->get_result();

    $ocupadas=[];
    while($r=$res->fetch_assoc()){
        $ocupadas[]=substr($r['hora'],0,5);
    }

    return array_diff($horas,$ocupadas);
}

// FLUJO
if(isset($_POST['opcion'])){
    $_SESSION['paso']='nombre';
}

if(isset($_POST['nombre'])){
    $_SESSION['nombre']=$_POST['nombre'];
    $_SESSION['paso']='email';
}

if(isset($_POST['email'])){
    $_SESSION['email']=$_POST['email'];
    $_SESSION['paso']='medico';
}

if(isset($_POST['medico'])){
    $_SESSION['medico']=$_POST['medico'];
    $_SESSION['paso']='fecha';
}

if(isset($_POST['fecha'])){
    $fecha = $_POST['fecha'];
    $hoy = date("Y-m-d");

    if($fecha < $hoy){
        $_SESSION['error']="❌ No puede seleccionar una fecha pasada.";
    }
    elseif(date('N',strtotime($fecha))>=6){
        $_SESSION['error']="❌ Lo sentimos, no atendemos fines de semana.";
    }
    else{
        $_SESSION['fecha']=$fecha;
        $_SESSION['horas']=horasDisponibles($conn,$fecha,$_SESSION['medico']);
        $_SESSION['paso']='hora';
    }
}

if(isset($_POST['hora'])){
    $hora = $_POST['hora'];

    $stmt=$conn->prepare("INSERT INTO clientes(nombre,email,opcion,fecha,hora,medico) VALUES(?, ?, 'cita', ?, ?, ?)");
    $stmt->bind_param("sssss",
        $_SESSION['nombre'],
        $_SESSION['email'],
        $_SESSION['fecha'],
        $hora,
        $_SESSION['medico']
    );
    $stmt->execute();

    enviarCorreo($_SESSION['email'],$_SESSION['nombre'],$_SESSION['fecha'],$hora,$_SESSION['medico']);

    $_SESSION['mensaje']="✅ Su cita ha sido agendada correctamente.<br>
📩 Recibirá un correo electrónico con los detalles.<br><br>
👤 ".$_SESSION['nombre']."<br>
📧 ".$_SESSION['email']."<br>
👨‍⚕️ ".$_SESSION['medico']."<br>
📅 ".$_SESSION['fecha']." a las ".$hora;

    $_SESSION['paso']='menu';
}

$paso=$_SESSION['paso'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Clínica SaludPlus</title>

<style>
/* TU CSS ORIGINAL — NO TOCADO */
body{
    font-family:Segoe UI;
    background:url('https://images.unsplash.com/photo-1588776814546-ec7e7c7f3b0e') center/cover;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.chat{
    width:420px;
    background:rgba(255,255,255,0.95);
    border-radius:20px;
    overflow:hidden;
}

.header{
    background:#2c3e50;
    color:white;
    padding:15px;
    text-align:center;
}

.msg{
    background:#e9eef2;
    padding:16px;
    border-radius:16px;
    width:95%;
    margin:0 auto;
    text-align:center;
}

.chat-box{
    padding:20px;
    display:flex;
    flex-direction:column;
    gap:18px;
}

.form-area{
    padding:20px;
    display:flex;
    flex-direction:column;
    gap:12px;
}

button{
    width:100%;
    padding:16px;
    border:none;
    border-radius:14px;
    background:#3b8ec2;
    color:white;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    background:#2f6f99;
}

.reset{
    background:#7f8c8d;
}

input{
    padding:14px;
    border-radius:12px;
    border:1px solid #ccc;
    text-align:center;
}
</style>

<script>
function mostrarContacto(){
    document.getElementById("chat-box").innerHTML += 
    "<div class='msg'>📞 Teléfono: 900 123 456<br>📧 Email: clinica@email.com</div>";
}

function mostrarHorario(){
    document.getElementById("chat-box").innerHTML += 
    "<div class='msg'>🕐 Lunes a Viernes: 9:00 - 18:00</div>";
}
</script>

</head>

<body>

<div class="chat">
<div class="header">💬 Clínica SaludPlus</div>

<div class="chat-box" id="chat-box">

<?php
if($paso=='inicio'){
    echo "<div class='msg'>👋 Bienvenido a Clínica SaludPlus.<br>¿En qué podemos ayudarle?</div>";
}

if(isset($_SESSION['mensaje'])){
    echo "<div class='msg'>".$_SESSION['mensaje']."</div>";
    unset($_SESSION['mensaje']);
}

if(isset($_SESSION['error'])){
    echo "<div class='msg'>".$_SESSION['error']."</div>";
    unset($_SESSION['error']);
}

if($paso=='menu'){
    echo "<div class='msg'>¿Cómo podemos ayudarle ahora?</div>";
}

if($paso=='nombre') echo "<div class='msg'>👤 Indique su nombre y apellidos:</div>";
if($paso=='email') echo "<div class='msg'>📧 Introduzca su correo electrónico:</div>";
if($paso=='medico') echo "<div class='msg'>👨‍⚕️ Seleccione especialista:</div>";
if($paso=='fecha') echo "<div class='msg'>📅 Seleccione una fecha:</div>";
if($paso=='hora') echo "<div class='msg'>🕐 Seleccione una hora:</div>";
?>

</div>

<div class="form-area">

<form method="POST">

<?php if($paso=='inicio' || $paso=='menu'){ ?>

<button name="opcion">📅 Solicitar cita</button>
<button type="button" onclick="mostrarContacto()">📞 Contacto</button>
<button type="button" onclick="mostrarHorario()">🕐 Horario</button>

<?php } elseif($paso=='nombre'){ ?>

<input name="nombre" placeholder="Nombre y apellidos" required>
<button>Continuar</button>

<?php } elseif($paso=='email'){ ?>

<input type="email" name="email" placeholder="Correo electrónico" required>
<button>Continuar</button>

<?php } elseif($paso=='medico'){ ?>

<button name="medico" value="Dr. García">Dr. García</button>
<button name="medico" value="Dra. Gallardo">Dra. Gallardo</button>
<button name="medico" value="Dr. López">Dr. López</button>

<?php } elseif($paso=='fecha'){ ?>

<input type="date" name="fecha" min="<?php echo date('Y-m-d'); ?>" required>
<button>Continuar</button>

<?php } elseif($paso=='hora'){ ?>

<?php 
if(!empty($_SESSION['horas'])){
    foreach($_SESSION['horas'] as $h){
        echo "<button name='hora' value='$h'>$h</button>";
    }
}else{
    echo "<div class='msg'>❌ No hay horas disponibles ese día.</div>";
}
?>

<?php } ?>

</form>

<form method="POST">
<button name="volver" class="reset">⬅ Volver</button>
<button name="reset" class="reset">🔄 Reiniciar</button>
</form>

</div>
</div>

</body>
</html>