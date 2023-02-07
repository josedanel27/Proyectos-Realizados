<?php

    session_start();
    require '../../Modulos/ConnectionDB.php';

    //Seccion comprobacion si es un usuario valido
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
        

    } else {
        echo '<script language="javascript">alert("No tiene el permiso para estar aquí!");</script>';
        header('Location: ../../index.php');
    }


    //Seccion para añadir elemento al menu

    if (isset($_POST["bt_agregar"])){
        if(!empty($_POST["nplato"]) && !empty($_POST["tplato"]) && !empty($_POST["pplato"]) && !empty($_POST["dplato"])){
            
            //Envio de consulta a la base de datos
            $sql = "INSERT INTO TB_MENU (NOMBRE_PLATO,TIPO_PLATO,PRECIO,DETALLE_PLATO,ESTADO_PLATO) VALUES (:nom,:tip,:prec,:deta,'Activo')";
            $datos = $connet -> prepare($sql);
            $datos -> bindParam(':nom', $_POST["nplato"]);
            $datos -> bindParam(':tip', $_POST["tplato"]);
            $datos -> bindParam(':prec', $_POST["pplato"]);
            $datos -> bindParam(':deta', $_POST["dplato"]);

            try{
                $datos->execute();
                header("Location: EditarMenu.php");
            }catch (error $e){
                echo($e);
            }

        } else {
            echo '<script language="javascript">alert("Llene todos los campos!");</script>';
        }
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
        
       <div class="Formulario">
           <div class="TituloForm">
               <h2>Formulario para Manu</h2>
           </div>

           <div class="entradas">
               <form action="" method="post">
                   <div class="pregun">
                       <h4>Nombre del Plato:</h4>
                       <input type="text" name="nplato" placeholder="Arroz con huevo">
                   </div>
                   <div class="pregun">
                       <h4>Tipo de plato (#):</h4>
                       <input type="text" name="tplato" placeholder="1:Entradas - 2:Fuerte - 3:Postre - 4:Bebidas">
                   </div>
                   <div class="pregun">
                       <h4>Precio del Plato:</h4>
                       <input type="text" name="pplato" placeholder="8.35">
                   </div>
                   <div class="pregun">
                       <h4>Detalles del Plato:</h4>
                       <input type="text" name="dplato" placeholder="Viene con arroz frito y otras cosas.">
                   </div>

                   <input type="submit" value="Añadir" name="bt_agregar" class="boton_login">
               </form>
           </div>

       </div> 

    </main>

    <footer>
        <h3>Creada por JP Developer &copy;| Todos los derechos reservado </h3>
    </footer>
</body>
</html>