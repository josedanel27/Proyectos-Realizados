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
        $sql = "SELECT * FROM TB_MENU";
        $datos = $connet -> prepare($sql);
        $datos -> execute();
        $menu = $datos->fetchAll(PDO::FETCH_ASSOC);
        
        $NFilas = count($menu);


    } else {
        echo '<script language="javascript">alert("No tiene el permiso para estar aquí!");</script>';
        header('Location: ../../index.php');
    }

    //Seccion de funcionalidad para botones de la tabla

    //Boton de desactivar elemento del menu
    if(isset($_GET["BT_DESAC"])){
        $sql = "UPDATE TB_MENU SET ESTADO_PLATO = 'Desactivado' WHERE ID_PLATO = :id";
        $datos = $connet -> prepare($sql);
        $datos -> bindParam(':id',$_GET["BT_DESAC"]);
        $datos -> execute();
        header("Location: EditarMenu.php");
    }

    //Boton de activar elemento del menu
    if(isset($_GET["BT_ACT"])){
        $sql = "UPDATE TB_MENU SET ESTADO_PLATO = 'Activo' WHERE ID_PLATO = :id";
        $datos = $connet -> prepare($sql);
        $datos -> bindParam(':id',$_GET["BT_ACT"]);
        $datos -> execute();
        header("Location: EditarMenu.php");
    }

    //Boton para actualizar elemento del menu

    //Se hizo que directo de la etiqueta del boton se enviara al formulario.
    

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
                <h2>Tabla de Menú</h2>
            </div>

            <div class="Tablaa">
                <table>
                    <thead>
                        <tr>
                            <th>Id Plato</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Detalles</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //Inicio del ciclo para imprimir lista del menu
                            $indice = 0;
                            while ($indice < $NFilas){

                        ?>
                        <tr>
                            <th><?php echo($menu[$indice]["ID_PLATO"]);?></th>
                            <th><?php echo($menu[$indice]["NOMBRE_PLATO"]);?></th>
                            <th><?php echo($menu[$indice]["TIPO_PLATO"]);?></th>
                            <th><?php echo($menu[$indice]["PRECIO"]);?></th>
                            <th><?php echo($menu[$indice]["DETALLE_PLATO"]);?></th>
                            <th><?php echo($menu[$indice]["ESTADO_PLATO"]);?></th>
                            
                            <th>
                                <?php
                                
                                    if($menu[$indice]["ESTADO_PLATO"] == "Activo"){
                                        ?>
                                            <form action="" method="get">
                                                <button name="BT_DESAC" value=<?php echo($menu[$indice]["ID_PLATO"]);?>>Desactivar</button>
                                            </form>
                                        <?php
                                    }

                                    if($menu[$indice]["ESTADO_PLATO"] == "Desactivado"){
                                        ?>
                                            <form action="" method="get">
                                                <button name="BT_ACT" value=<?php echo($menu[$indice]["ID_PLATO"]);?>>Activar</button>
                                            </form>
                                        <?php
                                    }

                                    ?>
                                            <form action="UpMenu.php" method="get">
                                                <button name="BT_UPDA" value=<?php echo($menu[$indice]["ID_PLATO"]);?>>Actualizar</button>
                                            </form>
                                        <?php
                                ?>
                            </th>
                           
                            
                        </tr>
                        <?php
                            //fin del ciclo de impresion
                            $indice += 1;
                            }

                        ?>
                    </tbody>
                </table>

                

                <div class="botones">

                    <div class="botonn">
                        <a href="AddMenu.php" class="boton">Añadir Nuevo</a>
                    </div>
                    <div class="botonn">
                        <a href="admin.php" class="boton">Atras</a>
                    </div>
                    
                </div>
        </div>


            </div>

        </section>

    </main>

    <footer>
        <h3>Creada por JP Developer &copy;| Todos los derechos reservado </h3>
    </footer>
</body>
</html>