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


    //Seccion para consulta a bd del elemento del menu a editar
    
    try{
        $sql = "SELECT * FROM TB_MENU WHERE ID_PLATO = :id";
        $datos = $connet -> prepare($sql);
        $datos -> bindParam(':id', $_GET['BT_UPDA']);
        $datos -> execute();
        $elemento = $datos->fetch(PDO::FETCH_ASSOC);

        
        }
    catch (error $e){
        echo($e);
    }
    
    // Seccion de envio de formulario corregido.
    
    if (isset($_POST["bt_update"])){
        
        if(!empty($_POST["nplato"]) && !empty($_POST["tplato"]) && !empty($_POST["pplato"]) && !empty($_POST["dplato"])){
            try{
            $sql = "UPDATE TB_MENU SET NOMBRE_PLATO = :nom, TIPO_PLATO =:tip , PRECIO =:prec , DETALLE_PLATO = :deta  WHERE ID_PLATO = :id";
            $datos = $connet -> prepare($sql);
            $datos -> bindParam(':nom', $_POST["nplato"]);
            $datos -> bindParam(':tip', $_POST["tplato"]);
            $datos -> bindParam(':prec', $_POST["pplato"]);
            $datos -> bindParam(':deta', $_POST["dplato"]);
            $datos -> bindParam(':id', $_GET["BT_UPDA"]);

            $datos -> execute();

            header("Location: EditarMenu.php");
            } catch (error $e){
                echo($e);
            }

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
               <h2>Actualizar elemento del Menu</h2>
           </div>

           <div class="entradas">
               <form action="" method="post">
                   <div class="pregun">
                       <h4>Nombre del Plato:</h4>
                       <input type="text" name="nplato" value="<?php echo($elemento["NOMBRE_PLATO"]);?>">
                   </div>
                   <div class="pregun">
                       <h4>Tipo de plato (#):</h4>
                       <input type="text" name="tplato" value="<?php echo($elemento["TIPO_PLATO"]);?>">
                   </div>
                   <div class="pregun">
                       <h4>Precio del Plato:</h4>
                       <input type="text" name="pplato" value="<?php echo($elemento["PRECIO"]);?>">
                   </div>
                   <div class="pregun">
                       <h4>Detalles del Plato:</h4>
                       <input type="text" name="dplato" value="<?php echo($elemento["DETALLE_PLATO"]);?>">
                   </div>

                   <input type="submit" value="Actualizar" name="bt_update" class="boton_login">
               </form>
           </div>

       </div> 

    </main>

    <footer>
        <h3>Creada por JP Developer &copy;| Todos los derechos reservado </h3>
    </footer>
</body>
</html>