<?php

session_start();
require '../../Modulos/ConnectionDB.php';

if (isset($_SESSION['user_id'])){
    //consulta para buscar el nombre del usuario
    $sql = "SELECT * FROM TB_USUARIO WHERE ID_TRABAJADOR = :id";
    $datos = $connet -> prepare($sql);
    $datos -> bindParam(':id', $_SESSION['user_id']);
    $datos -> execute();
    $registro = $datos->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if(count($registro) > 0){
        $user = $registro;

        if($user['PUESTO'] != 3){
            echo '<script language="javascript">alert("No tiene el permiso para estar aquí!");</script>';
            header('Location: ../../index.php');
        }

    }
    
    //Consulta para extraer datos de la tabla menu
    $sql = "SELECT * FROM TB_ORDEN WHERE ESTADO = 'TERMINADO'";
    $datos = $connet -> prepare($sql);
    $datos -> execute();
    $ventas = $datos->fetchAll(PDO::FETCH_ASSOC);
    
    $NFilas = count($ventas);


} else {
    echo '<script language="javascript">alert("No tiene el permiso para estar aquí!");</script>';
    header('Location: ../../index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../static/css/style.css">
    <link rel="shortcut icon" href="../../static/img/logo.jpeg" type="image/jpeg">
    <title>Sistema de Pedido</title>
</head>
<body>
    <head>
        <div class="encabezado">
            <div class="logo">
                <img src="../../static/img/logo.jpeg">
            </div>
            <div class="titulo">
                <h1>Sistema de Ordenes</h1>
            </div>
            <div class="usuario">
                <h4>Usuario:<?php echo($user['NOMBRE_TRABAJADOR']); ?></h4>
            </div>
        </div>
    </head>

    <main>
        
        <section class="ContenedorTabla">
            <div class="TituloTabla">
                <h2>Tabla Ventas</h2>
            </div>
            <div class="Tablaa">
            <table>
                <thead>
                    <tr>
                        <th>Id Orden</th>
                        <th>Salonero</th>
                        <th>Monto de la cuenta</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>

        </div>
        </section>
        
        <div class="botones">
            <div class="botonn">
                <a href="admin.php" class="boton">Atras</a>
            </div>
        </div>

    </main>

    <footer>
        <h3>Creada por JP Developer &copy;| Todos los derechos reservado </h3>
    </footer>
</body>
</html>