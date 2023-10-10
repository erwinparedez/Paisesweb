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

// Verificar si se recibió el nombre del país por POST
if (isset($_POST['pais'])) {
    // Obtener el nombre del país y limpiarlo de caracteres especiales
    $pais = filter_var($_POST['pais'], FILTER_SANITIZE_STRING);
    // Preparar la consulta SQL para buscar el país en la tabla
    $sql = 'SELECT * FROM pais WHERE NombrePais = :nombre';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $pais);
    // Ejecutar la consulta y obtener el resultado
    $stmt->execute();
    $response = $stmt->fetch(PDO::FETCH_ASSOC);
    // Cerrar la conexión a la base de datos
    $stmt->closeCursor();
    // Verificar si se encontró el país
    if ($stmt->rowCount() > 0) {
        // Si se encontró el país, agregar la propiedad encontrado con valor verdadero al resultado
        $response['encontrado'] = true;
    } else {
        // Si no se encontró el país, crear un arreglo asociativo con la propiedad encontrado con valor falso
        $response = ['encontrado' => false];
    }
    // Enviar el resultado al cliente como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
