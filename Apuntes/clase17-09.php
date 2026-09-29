<?php
function insertar_producto():void {

    $productos = [
        ["id" => 1, "nombre" => "Teclado", "precio" => 25.50],
        ["id" => 2, "nombre" => "Ratón", "precio" => 15.99],
        ["id" => 3, "nombre" => "Monitor", "precio" => 149.00]
    ];

    $nuevoProducto = [
        "id" => 4,
        "nombre" => "Webcam",
        "precio" => 39.90
    ];

    $productos[] = $nuevoProducto; //Añade el nuevoProducto al final de array que ya tenemos creado.
    // array_push($productos, $nuevoProducto); //Esta es otra forma de hacerlo, tambien lo añade al final del array.

    print_r($productos);
    // var_dump($productos); //Otra forma de imprimir el array
}

function inserta_producto($producto):void {

     $productos = [
        ["id" => 1, "nombre" => "Teclado", "precio" => 25.50],
        ["id" => 2, "nombre" => "Ratón", "precio" => 15.99],
        ["id" => 3, "nombre" => "Monitor", "precio" => 149.00]
    ];

     $productos[] = $producto;

    echo '<br>';
    print_r($productos);
}

insertar_producto();


$nuevoProducto = [
        "id" => 4,
        "nombre" => "Webcam",
        "precio" => 39.90
];

inserta_producto($nuevoProducto);

?>

<html>

<!-- Action se deja vacio para que cuando se ejecute el php se vuelva a cargar todo el html de nuevo. Si le ponemos dentro el nombre de otro archivo.php se hará la ejecución en otro documento -->
    <form method="post" action="otro.php"> 
        <input type="text" name="nombre" placeholder="¿Que hay que insertar?">
        <!-- El step con any es para poder meter decimales -->
        <input type="number" step="any" name="precio" placeholder="Precio"> 
        <input type="hidden" name="accion" value="añadir">
        <button type="submit" class="btn-añadir">Añadir</button>
    </form>
    
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

    ?>
</html>
