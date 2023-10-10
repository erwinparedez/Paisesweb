<?php
// Conectarse a la base de datos Mysql
try {
    $pdo = new PDO('mysql:host=db4free.net;dbname=sisinfpaises;port=3306', 'sistemasinf', 'sistemasinf');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si ocurre algún error, enviar un mensaje de error al cliente
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}

// Verificar si se recibieron los datos del país por POST
if (isset($_POST['nombre']) && isset($_POST['extension'])) {
    // Obtener los datos del país y limpiarlos de caracteres especiales
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $extension = filter_var($_POST['extension'], FILTER_SANITIZE_STRING);
    // Preparar la consulta SQL para insertar el país en la tabla
    $sql = 'INSERT INTO pais (NombrePais, ExtensionPais) VALUES (:nombre, :extension)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':extension', $extension);
    // Ejecutar la consulta y verificar si se insertó el país
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        // Si se insertó el país, crear un arreglo asociativo con un mensaje de éxito
        $response = ['mensaje' => 'El país ' . $nombre . ' se agregó correctamente a la base de datos.'];
    } else {
        // Si no se insertó el país, crear un arreglo asociativo con un mensaje de error
        $response = ['mensaje' => 'Ocurrió un error al intentar agregar el país ' . $nombre . ' a la base de datos.'];
    }
    // Cerrar la conexión a la base de datos
    $stmt->closeCursor();
    // Enviar la respuesta al cliente como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
