<?php
    //Invocando modulos y funcion para el login
    session_start();
    require 'Modulos/ConnectionDB.php';
    
    //Confirmar que se dio al boton de iniciar
    if(isset($_POST['bt'])){
        
        //Confirmando que las variables de usurio y password tienen valores
        if (!empty($_POST['usuario']) && !empty($_POST['passwd'])){
            
            try{
                $datos = $connet -> prepare("SELECT * FROM TB_USUARIO WHERE USERNAME = :user");
                $datos -> bindParam(':user', $_POST['usuario']);
                $datos -> execute();
                $fila = $datos->fetch(PDO::FETCH_ASSOC);

                try{
                    
                    if ((count($fila) > 0 ) && ($fila['CONTRASENIA'] == $_POST['passwd'])){
                        
                        //print_r($fila);

                        //cargar ID al la variable de session
                        $_SESSION['user_id'] = $fila['ID_TRABAJADOR'];

                        //Determinar el tipo e usuario que va entrando en base a su puesto
                        if ($fila['PUESTO'] == 3){
                            header('Location: template/Administracion/admin.php');
                        } elseif ($fila['PUESTO'] == 2){
                            header('Location: template/Cocina/cocina.php');
                        } elseif ($fila['PUESTO'] == 1){
                            header('Location: template/Salonero/PanelPrincipal.php');
                        }

                    } else {
                        echo '<script language="javascript">alert("La contraseña es incorrecto!");</script>';
                    }

                } catch(error $e){
                    //echo($e);
                    echo '<script language="javascript">alert("El username es incorrecto!");</script>';

                }

            } catch(Error $e){
                //echo($e);
            }
            
        } else {
            echo '<script language="javascript">alert("Rellene todos los campos");</script>';
        }
 
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="static/css/style.css">
    <link rel="shortcut icon" href="static/img/logo.jpeg" type="image/jpeg">
    <title>Sistema de Pedido</title>
</head>
<body>
    <head>
        <div class="encabezado">
            <div class="logo">
                <img src="static/img/logo.jpeg">
            </div>
            <div class="titulo">
                <h1>Sistema de Ordenes</h1>
            </div>
        </div>
    </head>

    <main>
        <div class="separacion">
            <section class="login">
                
                <form action="index.php" method="post">
                    <h2 class="titulo_login">Inicie Sesión</h2>
                    <br>
                    <h3 class="texto_login">Usuario</h3>
                    <br>
                    <input type="text" name="usuario" placeholder="Nombre de usuario" class="input_login">
                    <br>
                    <h3 class="texto_login">Contraseña</h3>
                    <br>
                    <input type="password" name="passwd" placeholder="Contraseña" class="input_login">
                    <br>
                    <div class="sepa_boton"></div>
                        <input type="submit" value="Entrar" name="bt" class="boton_login">
                    </div>
                </form>

            </section>
        </div>
    </main>

    <footer>
        <h3>Creada por JP Developer &copy;| Todos los derechos reservado </h3>
    </footer>
</body>
</html>