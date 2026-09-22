<?php
session_start();

if (!isset($_SESSION['tareas'])) {
    $_SESSION['tareas'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {     

    if (isset($_POST['accion']) && $_POST['accion'] === 'añadir') {
        $descripcion = trim($_POST['tarea']); 
        if (!empty($descripcion)) {
            $nueva_tarea = [
                'id' => uniqid(),
                'descripcion' => $descripcion,
                'completada' => false
            ];
            $_SESSION['tareas'][] = $nueva_tarea;
        }
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
        $id_eliminar = $_POST['id'];
        $_SESSION['tareas'] = array_filter($_SESSION['tareas'], function($tarea) use ($id_eliminar) {
            return $tarea['id'] !== $id_eliminar;
        });
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'completar') {
        $id_completar = $_POST['id'];
        foreach ($_SESSION['tareas'] as &$tarea) {
            if ($tarea['id'] === $id_completar) {
                $tarea['completada'] = !$tarea['completada'];
            }
        }
        unset($tarea);
    }
    
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
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <section class="box">
        <h1>Lista de Tareas</h1>
        
        <form method="POST" action="listaTareas.php">
            <input type="hidden" name="accion" value="añadir">
            <input type="text" name="tarea" placeholder="Escribe tu tarea pendiente.." required>
            <button class="button--blue" type="submit">Añadir</button>
        </form>

        <div>
            <?php if (empty($_SESSION['tareas'])): ?>
                <p>No hay tareas</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($_SESSION['tareas'] as $tarea): ?>
                        <li class="tarea-item">
                            <!-- Texto con la clase estática 'tarea' y la dinámica 'completada' -->
                            <span class="tarea <?php echo $tarea['completada'] ? 'completada' : ''; ?>">
                                <?php echo $tarea['descripcion']; ?>
                            </span>

                            <!-- Bloque de acciones -->
                            <div class="acciones">
                                <form method="POST" action="listaTareas.php">
                                    <input type="hidden" name="accion" value="completar">
                                    <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
                                    <button class="button--green" type="submit">
                                        <?php echo $tarea['completada'] ? 'Desmarcar' : 'Completar'; ?>
                                    </button>
                                </form>

                                <form method="POST" action="listaTareas.php">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
                                    <button class="button--red" type="submit">Eliminar</button>
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