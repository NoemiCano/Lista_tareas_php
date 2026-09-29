<?php
session_start();
require_once 'db.php';

$error = "";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_ingresado = $_POST['usuario'] ?? '';
    $contrasena_ingresada = $_POST['contrasena'] ?? '';

    if (!empty($usuario_ingresado) && !empty($contrasena_ingresada)) {
        // Consultar en MySQL en lugar del array
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario AND contrasena = :contrasena");
        $stmt->execute([
            'usuario' => $usuario_ingresado,
            'contrasena' => $contrasena_ingresada
        ]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $_SESSION['usuario'] = $usuario['usuario'];
            header('Location: listaTareas_con_bbdd.php');
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    } else {
        $error = "Por favor, completa todos los campos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles_login.css?v=<?php echo time(); ?>">
</head>
<body>

    <section class="login">
        <div class="login__box">
            <h1 class="login__title">Inicio de sesión</h1>

            <?php if (!empty($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            
            <form action="" method="POST" class="login__form">
                <input class="login__input" type="text" name="usuario" value="" placeholder="Usuario">
                <input class="login__input" type="password" name="contrasena" value="" placeholder="Contraseña">
                <button class="login__button" type="submit">Acceder</button>
            </form>
        </div>
    </section>
</body>
</html>