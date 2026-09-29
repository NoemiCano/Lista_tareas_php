<?php

    $productos = [
        ["id" => 1, "nombre" => "Teclado", "precio" => 25.50],
        ["id" => 2, "nombre" => "Ratón", "precio" => 15.99],
        ["id" => 3, "nombre" => "Monitor", "precio" => 149.00]
    ];

    //Guarda los valores introducidos por el usuario a las variables
    $id = time();
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];

    $nuevoProducto = [
        "id" =>  $id,
        "nombre" => $nombre,
        "precio" => $precio
    ];

    //El isset es por si tiene valor
    if (isset($_POST["accion"]) && $_POST["accion"] === "añadir") 
        array_push($productos, $nuevoProducto);

    print_r($productos);

    //Esto es para que redirija de nuevo a la pagina donde estaba
    header('Location: formularioHTML.html');

?>