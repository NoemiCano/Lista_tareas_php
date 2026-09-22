<?php

// Conexión a la base de datos
$conexion = new PDO(
    "mysql:host=localhost;dbname=tienda;charset=utf8mb4",
    "root",
    "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// Crear la tabla productos si no existe
$conexion->exec("
    CREATE TABLE IF NOT EXISTS productos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        precio DECIMAL(10,2) NOT NULL
    )
");

/*
  Inserta un producto en la base de datos
  Usa consultas preparadas para evitar inyección SQL
*/
function inserta_producto(PDO $conexion, string $nombre, float $precio): void {
	
    $sql = "INSERT INTO productos (nombre, precio) VALUES (?, ?)";
    $sentencia = $conexion->prepare($sql);
    $sentencia->execute([$nombre, $precio]); // en el INSERT INTO se inserta primero nombre y después precio
											 // en el execute primero se tiene que poner el nombre y luego el precio
}

/*
  Devuelve todos los productos de la base de datos
 */
function listar_productos(PDO $conexion): array {
   
    $sql = "SELECT id, nombre, precio FROM productos ORDER BY id";
	
	$resultado = $conexion->query($sql);  // ejecuta el SELECT y devuelve un PDOStatement
										  // $resultado es un PDOStatement: los datos existen, pero aún no son un array PHP	
	$filas = $resultado->fetchAll(PDO::FETCH_ASSOC); // extrae las filas en formato asociativo
													 // todas las filas se guardan en un array
	return $filas; // retorna todas las filas de la tabla productos  
}

//  Procesar el formulario 
if (isset($_POST['accion']) && $_POST['accion'] === 'añadir') {
    
	if (isset($_POST['nombre'])) {
		
		$nombre = trim($_POST['nombre']);
	}

	if (isset($_POST['precio'])) {
		
		$precio = (float) $_POST['precio'];
	}

    if ($nombre !== '' && $precio > 0) {
		
        inserta_producto($conexion, $nombre, $precio);
    }
}

// Muestra los productos
$productos = listar_productos($conexion);

?>
<!DOCTYPE html>
<html>
<body>
    <form method="post" action="">
        <input type="text" name="nombre" placeholder="¿Qué hay que insertar?" required>
        <input type="number" step="any" name="precio" placeholder="Precio" required>
        <input type="hidden" name="accion" value="añadir">
        <button type="submit" class="btn-añadir">Añadir</button>
    </form>

    <h3>Productos:</h3>
    <ul>
        <?php foreach ($productos as $p): ?>
            <li>
                <?= htmlspecialchars($p['nombre']) ?>
                    /*El = de después del ? lo que hace es que ese código lo imprime por pantalla*/
                <?= number_format((float) $p['precio'], 2) ?> €
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>