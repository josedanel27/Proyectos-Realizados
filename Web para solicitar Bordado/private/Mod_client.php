<?php

    session_start();
    require '../BD_conex.php';
    
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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area para Cliente</title>
    <link rel="shortcut icon" href="img/logoB.png" type="image/x.icon">
    <link rel="stylesheet" href="style.css">
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
            <a href="Modulo_Cliente/Reali_Pedi.php">Hacer pedido</a>
            <a href="Modulo_Cliente/List_pedi.php">Revisar Pedidos</a>
            <a href="Modulo_Cliente/List_pag.php">Hacer pago</a>
            <a href="../logout.php">Cerrar Sesion</a>
        </nav>

        <div class = "Texto_Inicio">
            <h1>Bienvenido al panel de para cliente!</h1>
            <?php  if (!empty($user)): ?>
            <br>Hola, <?= "{$user['Nombre']} {$user['Apellido']}";?>
             <?php endif; ?>
        </div>

    </header>

    <main>
        <section class="contenedor"> 
                
            <h2 class = "titulo_botones">Seleccion a acción que desees realizar!</h2>
            <br>
            <div class="Section_botones">
                <div class="Botones">
                    <a href="Modulo_Cliente/Reali_Pedi.php" class="botones_inicio1">Realizar Pedido</a>
                </div>

                <div class="Botones">
                    <a href="Modulo_Cliente/List_pedi.php" class="botones_inicio2">Ver pedidos</a>
                </div>

                <div class="Botones">
                    <a href="Modulo_Cliente/List_pag.php" class = "botones_inicio3">Hacer pago</a>
                </div>
                <div class="Botones">
                    <a href="../logout.php" class="botones_inicio4">Cerrar Sesión</a>
                </div>

            </div>
        </section>
    </main>

    <footer>
        <h2 class="titulo-final">Copyright &copy; 2021 | Bordados S.A. Todos los derechos reservados.</h2>
    </footer>
</body>
</html>