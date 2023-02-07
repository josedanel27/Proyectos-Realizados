<?php

    session_start();

    require 'BD_conex.php';
    
    if (isset($_POST['buttom'])){
        //echo '<script language="javascript">alert("boton ok");</script>';
        if(!empty($_POST['email']) && !empty($_POST['password'])){
            $datos = $connet -> prepare("SELECT * FROM tb_usuario WHERE email = :imeil;");
            $datos-> bindParam(':imeil', $_POST['email']);
            $datos->execute();
            $fila = $datos->fetch(PDO::FETCH_ASSOC);

            try{
                if (count($fila) > 0 && password_verify($_POST['password'],$fila['contrasenia'])){
                    $_SESSION['user_id'] = $fila['ID_usuario'];
                    
                    echo($_SESSION['user_id']);
                    if($fila['Type_Us'] == 10){
                        header('Location: /Proyect_IS2/private/Mod_Adm.php');
                    } else {
                        header('Location: /Proyect_IS2/private/Mod_client.php');
                    }
                } else {
                    echo '<script language="javascript">alert("Contraseña incorrecta!");</script>';
                }
            }catch(Error $e){
                echo '<script language="javascript">alert("Usuario no existe, registrese.");</script>';
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
        <title>Login | Bordados S.A.</title>
        <link rel="shortcut icon" href="img/logoB.png" type="image/x.icon">
        <link rel="stylesheet" href="login.css">
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    </head>
    <body>
        <header >
            <div class = "Info_superior">
                <div class = "Texto_Superior">
                    <p><br>(+507) 000-0000, 0000-0000</p>
                </div>

                <div class = "Texto_Superior">
                    <p><br>Info@Bordados.com.pa</p>
                </div>
            </div>
            
            <nav>
                <a href="Index.php">INICIO</a>
                <a href="">PRODUCTOS</a>
                <a href="login.php">LOGIN</a>
                <a href="registro.php">REGISTRO</a>
            </nav>

            <div class = "Texto_Inicio">
                <h1>Login | Inicio de Sesion</h1>
            </div>

            <div class="Input-login">
                <form action="login.php" method="POST">
                    <h4>Email:</h4>
                    <input type="text" name="email" placeholder="Nombre de usuario">
                    <h4>Contraseña</h4>
                    <input type="password" name= "password" ><br>
                    <a href="register.html">No tiene cuenta</a> <br>
                    <input type="submit" name="buttom" value="Iniciar Sesión">
                </form>
            </div>

        </header>

        <main>
            
        </main>

        <footer>
            <h2 class="titulo-final">Copyright &copy; 2021 | Bordados S.A. Todos los derechos reservados.</h2>
        </footer>
    </body>
</html>