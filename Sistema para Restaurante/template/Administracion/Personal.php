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

    //consulta a tabla de trabajadores
    $sql = "SELECT * FROM TB_USUARIO";
    $datos = $connet -> prepare($sql);
    $datos -> execute();
    $filas = $datos -> fetchAll(PDO::FETCH_ASSOC);
    $Nfilas = count($filas);

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
                <h2>Tabla Trabajadores</h2>
            </div>

            <div class="Tablaa">
                <table>
                    <thead>
                        <tr>
                            <th>Id Trabajador</th>
                            <th>Nombre</th>
                            <th>Username</th>
                            <th>Contraseña</th>
                            <th>Puesto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //Inicio de ciclo para imprimir datos de la tabla de trabajadores
                            $indice = 0;
                            while ($indice < $Nfilas){
                        ?>
                        <tr>
                            <th><?php echo($filas[$indice]["ID_TRABAJADOR"]);?></th>
                            <th><?php echo($filas[$indice]["NOMBRE_TRABAJADOR"]);?></th>
                            <th><?php echo($filas[$indice]["USERNAME"]);?></th>
                            <th><?php echo($filas[$indice]["CONTRASENIA"]);?></th>
                            <th><?php echo($filas[$indice]["PUESTO"]);?></th>

                        </tr>

                        <?php
                            $indice += 1;
                            }
                        ?>
                    </tbody>
                </table>

                <div class="botones">

                    <div class="botonn">
                        <a href="AddTrabajador.php" class="boton">Añadir Trabajador</a>
                    </div>
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