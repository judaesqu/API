<?php

//Conexión a la base de datos

$host = 'localhost';
$dbname = 'zapatos';
$username = 'root';
$password = '16022018Jm';

try{
    $conexion = new PDO ("mysql:host=$host;dbname=$dbname", $username, $password);
    $conexion -> setAttribute (PDO:: ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e){
    die('Error en la conexión con la base de datos: ' . $e->getMessage());
}

//Listado general del calzado
if ($_SERVER['REQUEST_METHOD']=='GET' && !isset($_GET['id'])){
    $sql = "SELECT * FROM atributos";
    $statement = $conexion -> prepare($sql);
    $statement ->execute();
    $result = $statement ->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
    exit();
}

//Listado de atributo por id
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])){
    $calzadoId = $_GET['id'];
    $sql = "SELECT * FROM atributos WHERE id = :id";
    $statement = $conexion -> prepare ($sql);
    $statement -> bindParam (':id', $calzadoId);
    $statement -> execute();
    $result = $statement -> fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
    exit();
}

//Ingresar un nuevo tipo de atributo
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $input = $_POST;
    $tipocalzado = $input['tipocalzado'];
    $marca = $input['marca'];
    $material = $input['material'];
    $talla = $input['talla'];
    $color = $input['color'];

    $sql = "INSERT INTO atributos (tipocalzado, marca, material, talla, color) VALUES (:tipocalzado, :marca, :material, :talla, :color)";
    $statement = $conexion ->prepare($sql);
    $statement -> bindparam (':tipocalzado', $tipocalzado);
    $statement -> bindparam (':marca', $marca);
    $statement -> bindparam (':material', $material);
    $statement -> bindparam (':talla', $talla);
    $statement -> bindparam (':color', $color);
    $statement -> execute();

    $calzadoId = $conexion -> lastInsertId();
    $response = [
        'id' => $calzadoId,
        'tipocalzado' => $tipocalzado,
        'marca' => $marca,
        'material' => $material,
        'talla' => $talla,
        'color' => $color,
        'message' => 'Registro ingresado correctamente'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

//Modificar un atributo de calzado
if ($_SERVER['REQUEST_METHOD']=='PUT' && isset($_GET['id'])){
    $input = json_decode(file_get_contents('php://input'),true);
    $calzadoId = $_GET['id'];
    $tipocalzado = $input['tipocalzado'];
    $marca = $input['marca'];
    $material = $input['material'];
    $talla = $input['talla'];
    $color = $input['color'];

    $sql = "UPDATE atributos SET tipocalzado = :tipocalzado, marca = :marca, material = :material, talla = :talla, color = :color WHERE id = :id";
    $statement = $conexion->prepare($sql);
    $statement -> bindparam (':tipocalzado', $tipocalzado);
    $statement -> bindparam (':marca', $marca);
    $statement -> bindparam (':material', $material);
    $statement -> bindparam (':talla', $talla);
    $statement -> bindparam (':color', $color);
    $statement -> bindparam (':id', $calzadoId);
    $statement -> execute();

    $rowCount = $statement->rowCount();

    if($rowCount > 0){
        $response = array ('message' => 'La información ha sido actualizada exitosamente');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }else{
        $response = array ('message'=> 'La actualización no ha sido completada, revisa el id proporcionado');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

// Eliminación de un campo existente.

if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id'])) {
    $calzadoId = $_GET['id'];
    
    $sql = "DELETE FROM atributos WHERE id = :id";
    $statement = $conexion->prepare($sql);
    $statement -> bindparam (':id', $calzadoId);
    $statement->execute();
    
    $rowCount = $statement->rowCount();
    
    if ($rowCount > 0) {
        $response = array('message' => 'Eliminación exitosa');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        $response = array('message' => 'No se encontró el atributo con el Id proporcionado');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

?>

