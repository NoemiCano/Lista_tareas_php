<?php
// 1. Iniciamos la sesión para poder guardar las tareas en un array sin usar base de datos
session_start();

// 2. Si no existe el array de tareas en la sesión, lo creamos vacío
if (!isset($_SESSION['tareas'])) { //Se pone lo de 'tarea' para darle un nombre a ese array y que puedas tener varias variables de session abiertas y sin mezclar información
    $_SESSION['tareas'] = []; // aqui le estamos diciendo que lo cree vacio
}

// 3. PROCESAR EL FORMULARIO (Añadir, Eliminar o Marcar como completada)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // AÑADIR TAREA
    if (isset($_POST['accion']) && $_POST['accion'] === 'añadir') {
        $descripcion = trim($_POST['tarea']);
        if (!empty($descripcion)) {
            // Creamos la nueva tarea con su id, descripción y estado
            $nueva_tarea = [
                'id' => uniqid(), // Genera un ID único automáticamente
                'descripcion' => htmlspecialchars($descripcion),
                'completada' => false
            ];
            // La guardamos en el array de la sesión
            $_SESSION['tareas'][] = $nueva_tarea;
        }
    }

    // ELIMINAR TAREA
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
        $id_eliminar = $_POST['id'];
        // Filtramos el array para quitar la tarea con ese ID
        $_SESSION['tareas'] = array_filter($_SESSION['tareas'], function($tarea) use ($id_eliminar) {
            return $tarea['id'] !== $id_eliminar;
        });
    }

    // MARCAR COMO COMPLETADA / CAMBIAR ESTADO
    if (isset($_POST['accion']) && $_POST['accion'] === 'completar') {
        $id_completar = $_POST['id'];
        // Recorremos las tareas y cambiamos el estado de la que coincide con el ID
        foreach ($_SESSION['tareas'] as &$tarea) {
            if ($tarea['id'] === $id_completar) {
                $tarea['completada'] = !$tarea['completada']; // Si era false pasa a true, y viceversa
            }
        }
    }
    
    // Redireccionamos a la misma página para limpiar el envío del formulario y evitar duplicados al recargar
    header('Location: listaTareas.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Tareas PHP</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Un pequeño estilo rápido para tachar las tareas completadas */
        .completada { text-decoration: line-through; color: gray; }
        .tarea-item { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .acciones { display: inline; }
    </style>
</head>
<body>
    <section class="box">
        <h1>Lista de Tareas</h1>
        
        <!-- Formulario para AÑADIR tareas -->
        <form method="POST" action="listaTareas.php">
            <input type="hidden" name="accion" value="añadir">
            <input type="text" name="tarea" placeholder="Escribe tu tarea pendiente.." required>
            <button type="submit">Añadir</button>
        </form>

        <div>
            <?php if (empty($_SESSION['tareas'])): ?>
                <p>No hay tareas</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($_SESSION['tareas'] as $tarea): ?>
                        <li class="tarea-item">
                            <!-- Si la tarea está completada, le añadimos la clase CSS para tacharla -->
                            <span class="<?php echo $tarea['completada'] ? 'completada' : ''; ?>">
                                <?php echo $tarea['descripcion']; ?>
                            </span>

                            <!-- Formularios individuales para los botones de cada tarea -->
                            <div class="acciones">
                                <!-- Botón Marcar/Desmarcar -->
                                <form method="POST" action="listaTareas.php" style="display:inline;">
                                    <input type="hidden" name="accion" value="completar">
                                    <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
                                    <button type="submit">
                                        <?php echo $tarea['completada'] ? 'Desmarcar' : 'Completar'; ?>
                                    </button>
                                </form>

                                <!-- Botón Eliminar -->
                                <form method="POST" action="listaTareas.php" style="display:inline;">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
                                    <button type="submit">Eliminar</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>