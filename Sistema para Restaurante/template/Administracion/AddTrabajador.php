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


    //Seccion para añadir nuevo colaborador

    if (isset($_POST["bt_agregar"])){
        if (!empty($_POST['ncola']) && !empty($_POST['ucola']) && !empty($_POST['ccola']) && !empty($_POST['pcola'])){
            //enviendo peticion a la base de datos

            $sql = "INSERT INTO TB_USUARIO (NOMBRE_TRABAJADOR,USERNAME,CONTRASENIA,PUESTO) VALUES (:nom,:us,:con,:pues)";
            $datos = $connet -> prepare($sql);
            $datos -> bindParam(':nom', $_POST['ncola']);
            $datos -> bindParam(':us',$_POST['ucola']);
            $datos -> bindParam(':con',$_POST['ccola']);
            $datos -> bindParam(':pues',$_POST['pcola']);

            $datos -> execute();
            header("Location: Personal.php");
        } else {
            echo '<script language="javascript">alert("Los datos no estas completos!");</script>';
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
               <h2>Formulario para Colaborador</h2>
           </div>

           <div class="entradas">
               <form action="" method="post">
                   <div class="pregun">
                       <h4>Nombre Colaborador:</h4>
                       <input type="text" name="ncola" placeholder="Elsa Pito">
                   </div>
                   <div class="pregun">
                       <h4>Username:</h4>
                       <input type="text" name="ucola" placeholder="elpito08">
                   </div>
                   <div class="pregun">
                       <h4>Contraseña:</h4>
                       <input type="text" name="ccola" placeholder="Ejm: 123456789">
                   </div>
                   <div class="pregun">
                       <h4>Puesto (#):</h4>
                       <input type="text" name="pcola" placeholder="3:Administrador - 2: Cocinero - 1: Salonero">
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