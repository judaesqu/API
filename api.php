<?php

//Conectamos la base de datos

$servidor = "localhost";
$usuario = "root";
$contrasena = "16022018Jm";
$db = "practicaapi";

$conexion = new mysqli ($servidor, $usuario, $contrasena, $db);

//validacion de la conexión

if ($conexion -> connect_error) {
    die("Error, valida por favor tu conexión: " . $conexion -> connect_error);
}

//declaración de los datos del usuario
$user = $_POST["user"];
$password = $_POST ["password"];

//Autenticación del usuario

$sql = "SELECT * FROM user WHERE user = '$user' AND password = '$password'";
$result = $conexion -> query($sql);

//verificación de las credenciales proporcionadas (usuario)

if ($result -> num_rows > 0){
    //autenticación correcta del usuario
    $response = array ("status" => "success", "message" => "Autenticación Exitosa");
}
else{
    //En caso de no encontrar el usuario o que las credenciales sean erroneas
    $response = array ("status" => "error", "message" => "Revisa que las credenciales sean correctas");
}

//Respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

//Fin de la conexión a la base de datos

$conexion -> close ();

?>