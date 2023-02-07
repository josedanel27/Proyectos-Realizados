<?php

    session_start();
    require '../../BD_conex.php';
    
    if(isset($_SESSION['user_id'])){
        $sql = "SELECT * FROM tb_usuario WHERE ID_usuario = :id";
        $datos = $connet -> prepare($sql);
        $datos -> bindParam(':id', $_SESSION['user_id']);
        $datos -> execute();
        $registros = $datos->fetch(PDO::FETCH_ASSOC);

        $user = null;

        if(count($registros) > 0){
            $user = $registros;
        }
    } else {
        echo '<script language="javascript">alert("Accedo denegado, Inicie sesión!");</script>';
        header('Location: /Proyect_IS2');
    }

    if(isset($_POST['boton'])){
        $ruta = "../../Imag_Pedidos/{$_FILES['photo']['name']}";
        $nameimg = $_FILES['photo']['name'];
        $imgen = $_FILES['photo']['tmp_name'];

        $sql = "INSERT INTO tb_pedido (TIPO_SUETER, NOMBRE, LOGO_ARTE, TALLA, color, TELEFONO, ID_CLIENTE, ESTADO, POSI_LOGO) VALUE (:tip, :nombr, :logo, :talla, :color, :telefo, :clien, 'Por procesar', :posi)";
        $inserto = $connet -> prepare($sql);
        $inserto -> bindParam(':tip', $_POST['tipo']);
        $inserto -> bindParam(':nombr', $_POST['nombre']);
        $inserto -> bindParam(':logo', $nameimg);
        $inserto -> bindParam(':talla', $_POST['talla']);
        $inserto -> bindParam(':color', $_POST['color']);
        $inserto -> bindParam(':telefo', $_POST['telef']); 
        $inserto -> bindParam(':clien', $user['ID_usuario']);
        $inserto -> bindParam(':posi', $_POST['posic']);

        if ($inserto->execute()){
            move_uploaded_file($imgen,$ruta);
            echo '<script language="javascript">alert("Su pedido se realizo exitosamente!");</script>';
            header('Location: /Proyect_IS2/private/Mod_Client.php');
        } else {
            echo '<script language="javascript">alert("Ocurrio un error, intente nuevamente!");</script>';
            header('Location: /Proyect_IS2/private/Mod_Client.php');
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
        <link rel="stylesheet" href="style.css">
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
                <a href="../Mod_client.php">INICIO</a>
                <a href="">PRODUCTOS</a>
                <a href=""></a>
                <a href="../../logout.php">CERRAR SESION</a>
            </nav>

            <div class = "Texto_Inicio">
                <h1>Bienvenido a Bordados S.A.</h1>
                <h3>Nos dedicamos crear productos que te hagan sentir parte de tu asociacion o institución!</h3>
            </div>
        </header>
        

        <main>
            <section class="contenedor-registro">
                
                <h2 class = "titulo-registro">Formulario para ordenar pedido</h2>
                <h4>Los siguientes datos se registrarán dentro de nuestra base de datos.
                <br> Por favor ser coherente!
                </h4>
                <br><h4>Llene los espacios con la información requerida!</h4> <br>
                

                <div class="Input_register">
                    
                    <form action="Reali_pedi.php" method="POST" enctype="multipart/form-data">
                        <h5>Tipo de sueter:</h5>
                        <select name="tipo" required>
                            <option value=""></option>
                            <option value="polo">Tipo polo</option>
                            <option value="tortuga">Tipo cuello de tortuga</option>
                        </select>
                        <h5>Nombre que llevara el sueter:</h5>
                        <input type="text" name="nombre" placeholder="Ejm: Nelson Buitrago">

                        <h5>Carge el logo o arte que llevará su sueter:</h5>
                        <input type="file" name="photo" id="photo" required>

                        <h5>Posicion en la desea el logo:</h5>
                        <select name="posic" required>
                            <option value=""></option>
                            <option value="">---------------Para polo--------------</option>
                            <option value="izquierdo">Lado izquierdo</option>
                            <option value="derecho">Lado derecho</option>
                            <option value="">--------Para cuello de tortuga--------</option>
                            <option value="espalda-derecha">Espalda Grande y alfrente derecha</option>
                            <option value="espalda-derecho">Espalda grande y alfrente izquierdo</option>
                            <option value="derecha">Solo alfrente derecha</option>
                            <option value="izquierda">Solo alfrente izquierda</option>
                        
                        </select>

                        <h5>Talla del sueter:</h5>
                        <select name="talla" required>
                            <option value=""></option>
                            <option value="">-------------Para niños----------</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="4">4</option>
                            <option value="6">6</option>
                            <option value="10">10</option>
                            <option value="12">12</option>
                            <option value="14">14</option>
                            <option value="16">16</option>
                            <option value="20">20</option>
                            <option value="">-----------Para adultos----------</option>
                            <option value="xs">XS</option>
                            <option value="s">S</option>
                            <option value="m">M</option>
                            <option value="l">L</option>
                            <option value="xl">XL</option>
                            <option value="xxl">XXL</option>
                        </select>
                        <h5>Color:</h5>
                        <select name="color" required >
                            <option value=""></option>
                            <option value="negro">Negro</option>
                            <option value="azul">Azul</option>
                            <option value="rojo">Rojo</option>
                            <option value="verde">Verde</option>
                            <option value="gris">Gris</option>
                            <option value="amarillo">Amarillo</option>
                            <option value="rosa">Rosado</option>
                        </select>
                        <h5>Telefono:</h5>
                        <input type="text" name="telef" placeholder="Telefono" required> <br>
                        <center><input type="submit" name="boton" value="Enviar"></center>
                    </form>
                </div>
            </section>
        </main>

        <footer>
            <h2 class="titulo-final">Copyright &copy; 2021 | Bordados S.A. Todos los derechos reservados.</h2>
        </footer>
    </body>
</html>