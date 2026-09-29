<?php
session_start();

$usuarios = [
    ["usuario" => "noemi", "contrasena" => "1234"],
    ["usuario" => "laura", "contrasena" => "5678"],
    ["usuario" => "alex", "contrasena" => "abcd"],
];

$error = "";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_ingresado = $_POST['usuario'] ?? '';
    $contrasena_ingresada = $_POST['contrasena'] ?? '';

    $login_exitoso = false;

    foreach ($usuarios as $u) {
        if ($u['usuario'] === $usuario_ingresado && $u['contrasena'] === $contrasena_ingresada) {
            $login_exitoso = true;
            break;
        }
    }

    if ($login_exitoso) {
        $_SESSION['usuario'] = $usuario_ingresado;
        header('Location: listaTareas.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
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
                <p style="color: red;"><?php echo $error; ?></p>
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