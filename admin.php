<?php
session_start();
include("config/conexion.php");

if(!isset($_SESSION['admin'])){
header("Location: login.php");
exit();
}


/* ELIMINAR CITA */
if(isset($_GET["eliminar"])){

$id=(int)$_GET["eliminar"];

$conn->query("
DELETE FROM clientes
WHERE id_cliente=$id
");

header("Location: admin.php");
exit();
}



/* BUSCADOR */
$buscar="";

if(isset($_GET["buscar"])){

$buscar=$conn->real_escape_string($_GET["buscar"]);

$sql="
SELECT *
FROM clientes
WHERE nombre LIKE '%$buscar%'
OR medico LIKE '%$buscar%'
ORDER BY fecha DESC,hora DESC
";

}else{

$sql="
SELECT *
FROM clientes
ORDER BY fecha DESC,hora DESC
";

}

$result=$conn->query($sql);


/* DASHBOARD */
$total=$conn->query(
"SELECT COUNT(*) t FROM clientes"
)->fetch_assoc()['t'];

$hoy=date("Y-m-d");

$citasHoy=$conn->query(
"SELECT COUNT(*) t FROM clientes WHERE fecha='$hoy'"
)->fetch_assoc()['t'];

?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administración</title>

<style>

body{
font-family:'Segoe UI',sans-serif;
margin:0;
padding:40px;
background:linear-gradient(135deg,#667eea,#764ba2);
}

.container{
max-width:1200px;
margin:auto;
background:white;
padding:35px;
border-radius:25px;
box-shadow:0 20px 50px rgba(0,0,0,.18);
}

h1{
text-align:center;
margin-bottom:35px;
color:#2c3e50;
}

.search{
width:100%;
padding:15px;
font-size:16px;
border:1px solid #ddd;
border-radius:12px;
margin-bottom:20px;
}

.logout{
display:inline-block;
background:#e74c3c;
color:white;
padding:12px 18px;
border-radius:10px;
text-decoration:none;
font-weight:bold;
margin-bottom:30px;
}

.stats{
display:flex;
gap:20px;
margin-bottom:35px;
}

.card{
flex:1;
background:#eef2ff;
padding:30px;
border-radius:18px;
text-align:center;
box-shadow:0 8px 18px rgba(0,0,0,.08);
}

.card h2{
margin:0;
font-size:42px;
color:#4b5fd1;
}

.card p{
font-weight:bold;
}

table{
width:100%;
border-collapse:collapse;
border-radius:18px;
overflow:hidden;
}

th{
background:#2c3e50;
color:white;
padding:18px;
}

td{
padding:16px;
border-bottom:1px solid #ddd;
}

tr:hover{
background:#f8f9ff;
}

.acciones a{
text-decoration:none;
margin:0 6px;
font-size:22px;
}

.calendar{
background:#27ae60;
padding:8px 12px;
border-radius:8px;
color:white!important;
font-size:14px!important;
}

.calendar:hover{
background:#219150;
}

@media(max-width:900px){
.stats{
flex-direction:column;
}
}

</style>

</head>
<body>


<div class="container">


<form method="GET">

<input
class="search"
name="buscar"
type="text"
placeholder="Buscar por paciente o médico..."
value="<?php echo $buscar; ?>">

</form>



<a class="logout" href="logout.php">
Cerrar sesión
</a>



<h1>
📊 Panel de Administración
</h1>



<div class="stats">

<div class="card">
<h2><?php echo $total; ?></h2>
<p>Total citas</p>
</div>

<div class="card">
<h2><?php echo $citasHoy; ?></h2>
<p>Citas hoy</p>
</div>

</div>




<table>

<tr>
<th>ID</th>
<th>Fecha</th>
<th>Hora</th>
<th>Médico</th>
<th>Acciones</th>
</tr>


<?php while($row=$result->fetch_assoc()){

$id=$row["id_cliente"];
$fecha=$row["fecha"];
$hora=$row["hora"];
$medico=$row["medico"];


/* LINK GOOGLE CALENDAR */

$fechaGoogle=str_replace("-","",$fecha);
$horaGoogle=str_replace(":","",$hora);

$inicio=$fechaGoogle."T".$horaGoogle."00";
$fin=$fechaGoogle."T".$horaGoogle."00";

$link=
"https://calendar.google.com/calendar/render?action=TEMPLATE".
"&text=Cita+Medica".
"&dates=".$inicio."/".$fin.
"&details=Cita+con+".urlencode($medico);

?>

<tr>

<td>
<?php echo $id; ?>
</td>

<td>
<?php echo $fecha; ?>
</td>

<td>
<?php echo $hora; ?>
</td>

<td>
<?php echo $medico; ?>
</td>


<td class="acciones">

<a href="editar.php?id=<?php echo $id; ?>">
✏️
</a>

<a
onclick="return confirm('¿Eliminar cita?')"
href="admin.php?eliminar=<?php echo $id; ?>">
🗑️
</a>

<a
class="calendar"
target="_blank"
href="<?php echo $link; ?>">
📅 Google Calendar
</a>

</td>

</tr>

<?php } ?>

</table>


</div>

</body>
</html>