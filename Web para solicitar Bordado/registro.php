<?php
    $vali = 0;
    require 'BD_conex.php';

    if (isset($_POST['button'])){

        if (!empty($_POST['email'])&& !empty($_POST['password'])){
            if (!empty($_POST['anio']) && (2021 - $_POST['anio']) < 18){
                echo '<script language="javascript">alert("Eres menor de edad!");</script>';
                $vali = 1;
            }

            if ($_POST['password'] != $_POST['password1']){
                echo '<script language="javascript">alert("Las contraseñas no coinciden, Verificar!");</script>';
                $vali = 1;
            }

            if(empty($_POST['nombre']) && empty($_POST['apellido']) && empty($_POST['telefono'])){
                echo '<script language="javascript">alert("Compruebe los campos con * esten llenos.");</script>';
                $vali = 1;
            }
            
            if ($vali == 0){
                
                $sql = "INSERT INTO tb_usuario (ID_usuario, Nombre, Apellido, Cedula, Telefono, email, username, contrasenia, anio_nacimiento, Type_Us) VALUES (NULL,:nombr, :apellid, :cedul, :telefon, :imeil, 'clit', :passw, :ani, '35');";
                $stmt = $connet -> prepare ($sql); 
                $stmt -> bindParam(':nombr', $_POST['nombre']);
                $stmt -> bindParam(':apellid', $_POST['apellido']);
                $stmt -> bindParam(':cedul', $_POST['cedula']);
                $stmt -> bindParam(':telefon', $_POST['telefono']);
                $stmt -> bindParam(':imeil', $_POST['email']);
                $stmt -> bindParam(':ani', $_POST['anio']);
                $passww = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $stmt -> bindParam(':passw', $passww);
                
                if($stmt->execute()){
                    echo '<script language="javascript">alert("Se a registrado exitosamente!");</script>';
                    die(header('Location: /Proyect_IS2/login.php'));
                } else {
                    echo '<script language="javascript">alert("Lo lamentamos, hubo un problema. :(");</script>';
                }
            }
            
        } else {
            echo '<script language="javascript">alert("Error, compruebe que todos los campos esten llenos!");</script>';
        }

    }
    

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content = "width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content = "ie_edge"> 
        <title>REGISTRO | Bordados S.A.</title>
        <link rel="shortcut icon" href="img/logoB.png" type="image/x.icon">
        <link rel="stylesheet" href="disenio1.css">
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    </head>

    <body>
        <header>
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
                <a href="Login.php">LOGIN</a>
                <a href="registro.php">REGISTRO</a>
            </nav>

            <div class = "Texto_Inicio">
                <h1>Bienvenido a Bordados S.A.</h1>
                <h3>Nos dedicamos crear productos que te hagan sentir parte de tu asociacion o institución!</h3>
            </div>
        </header>
        

        <main>
            <section class="contenedor-registro">
                
                <h2 class = "titulo-registro">Formulario de registro</h2>
                <h4>Los siguientes datos se registrarán dentro de nuestra base de datos.
                <br> Por favor ser coherente!
                </h4>
                <br><h4>Llene los espacios con la información requerida!</h4> <br>
                

                <div class="Input_register">
                    
                    <form action="registro.php" method="POST">
                        <h5>Introduzca su nombre*:</h5>
                        <input type="text" name="nombre" placeholder="Nombre">
                        <h5>Introduzca su apellido*:</h5>
                        <input type="text" name="apellido" placeholder="Apellido">
                        <h5>Introduzca su numero de indentidad personal:</h5>
                        <input type="text" name="cedula" placeholder="Cedula">
                        <h5>Introduzca su numero telefonico de contacto*:</h5>
                        <input type="text" name="telefono" placeholder="Telefono">
                        <h5>Introduzca su dirección de correo electronico*:</h5>
                        <input type="text" name="email" placeholder="Email">
                        <h5>Introduzca un Contraseña para su nombre de usuario*:</h5>
                        <input type="text" name="password" placeholder="Contraseña">
                        <h5>Introduzca nuevamente la contraseña*</h5>
                        <input type="text" name="password1" placeholder="Contraseña">
                        <h5>Introduzca su año de nacimiento:*</h5>
                        <input type="text" name="anio" placeholder="Año de nacimiento"> <br><br>
                        <input type="submit" name ="button" value="Enviar">
                    </form>
                </div>
            </section>
        </main>

        <footer>
            <h2 class="titulo-final">Copyright &copy; 2021 | Bordados S.A. Todos los derechos reservados.</h2>
        </footer>
    </body>
</html>

