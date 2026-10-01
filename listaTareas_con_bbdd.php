<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login_con_bbdd.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['accion']) && $_POST['accion'] === 'añadir') {
        $descripcion = trim($_POST['tarea']); 
        if (!empty($descripcion)) {
            $stmt = $pdo->prepare("INSERT INTO tareas (descripcion, completada) VALUES (:descripcion, 0)");
            $stmt->execute(['descripcion' => $descripcion]);
        }
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
        $id_eliminar = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM tareas WHERE id = :id");
        $stmt->execute(['id' => $id_eliminar]);
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'completar') {
        $id_completar = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE tareas SET completada = NOT completada WHERE id = :id");
        $stmt->execute(['id' => $id_completar]);
    }
    
    header('Location: listaTareas_con_bbdd.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM tareas ORDER BY id DESC");
$tareas = $stmt->fetchAll();
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
        
        <form method="POST" action="listaTareas_con_bbdd.php">
            <input type="hidden" name="accion" value="añadir">
            <input type="text" name="tarea" placeholder="Escribe tu tarea pendiente.." required>
            <button class="button--blue" type="submit">Añadir</button>
        </form>

        <div>
            <?php if (empty($tareas)): ?>
                <p>No hay tareas</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($tareas as $tarea): ?>
                        <li class="tarea-item">
                            <span class="tarea <?php echo $tarea['completada'] ? 'completada' : ''; ?>">
                                <?php echo htmlspecialchars($tarea['descripcion']); ?>
                            </span>

                            <div class="acciones">
                                <form method="POST" action="listaTareas_con_bbdd.php">
                                    <input type="hidden" name="accion" value="completar">
                                    <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
                                    <button class="button--green" type="submit">
                                        <?php echo $tarea['completada'] ? 'Desmarcar' : 'Completar'; ?>
                                    </button>
                                </form>

                                <form method="POST" action="listaTareas_con_bbdd.php">
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