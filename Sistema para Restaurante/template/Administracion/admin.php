<?php

    session_start();
    require '../../Modulos/ConnectionDB.php';

    if (isset($_SESSION['user_id'])){
        
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
        
        <div class="botones">

            <div class="botonn">
                <a href="EditarMenu.php" class="boton">Editar Menu</a>
            </div>
            <div class="botonn">
                <a href="Personal.php" class="boton">Personal</a>
            </div>
            <div class="botonn">
                <a href="ListaVentas.php" class="boton">Lista de ventas</a>
            </div>
            <div class="botonn">
                <a href="../../logout.php" class="boton">Cerrar Sesión</a>
            </div>
        </div>

    </main>

    <footer>
        <h3>Creada por JP Developer &copy;| Todos los derechos reservado </h3>
    </footer>
</body>
</html>