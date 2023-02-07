<?php

    session_start();
    require '../../BD_conex.php';
    
    if(isset($_SESSION['user_id'])){
        $sql = "SELECT * FROM tb_pedido ";
        $datos = $connet -> prepare($sql);
        $datos -> execute();
        $registros = $datos->fetchAll(PDO::FETCH_ASSOC);
        $user = null;

         
        try {
            if(count($registros) > 0){
                $pedidos = $registros;
                $filas = count($pedidos);
            }
        } catch(error $e){
            
        }
    } else {
        echo '<script language="javascript">alert("Accedo denegado, Inicie sesión!");</script>';
        header('Location: /Proyect_IS2');
    }

    if(isset($_GET['btpagar'])){
        $sql = "UPDATE tb_pedido SET ESTADO = 'Por pagar' WHERE ID_PEDIDO = :id";
        $actuali = $connet -> prepare($sql);
        $actuali -> bindParam(':id', $_GET['btpagar']);

        if($actuali->execute()){
            header('Location: /Proyect_IS2/private/Mod_Adm.php');
        }
    }

    if(isset($_GET['btinsumos'])){
        $sql = "UPDATE tb_pedido SET ESTADO = 'Falta Insumo' WHERE ID_PEDIDO = :id";
        $actuali = $connet->prepare($sql);
        $actuali -> bindParam(':id', $_GET['btinsumos']);

        if($actuali -> execute()){
            header('Location: /Proyect_IS2/private/Mod_Adm.php');
        }
    }

    if (isset($_GET['btpagar'])){
        $sql = "SELECT * FROM tb_pedido WHERE ID_PEDIDO = :id ";
        $datos = $connet -> prepare($sql);
        $datos -> bindParam(':id', $_GET['btpagar']);
        $datos -> execute();
        $registros = $datos->fetch(PDO::FETCH_ASSOC);
        if(count($registros)>0){
            
            $sql = "INSERT INTO tb_pago (ID_PEDIDO, ESTADO, ID_Cliente) VALUE (:idpedi, 'Por pagar', :idclie)";
            $inser = $connet -> prepare($sql);
            $inser -> bindParam(':idpedi', $_GET['btpagar']);
            $inser -> bindParam(':idclie', $registros['ID_CLIENTE']);
            if($inser -> execute()){
                echo '<script language="javascript">alert("Se envio a pago un pedido!");</script>';
            }
        }
    }

    if(isset($_GET['btlisto'])){
        $sql = "UPDATE tb_pedido SET ESTADO = 'RECOGER, LISTO' WHERE ID_PEDIDO = :idp";
        $actuali = $connet -> prepare($sql);
        $actuali -> bindParam(':idp', $_GET['btlisto']);
        $actuali -> execute();
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
                <a href="../Mod_Adm.php">INICIO</a>
                <a href="">PRODUCTOS</a>
                <a href="../../logout.php">CERRAR SESION</a>
            </nav>

            <div class = "Texto_Inicio">
                <h1>Bienvenido a Bordados S.A.</h1>
                <h3>Nos dedicamos crear productos que te hagan sentir parte de tu asociacion o institución!</h3>
            </div>
        </header>
        

        <main>
            <section class="contenedor-tabla">
                <div><h2 class="titulo_list">Lista de pedidos</h2></div>
                <div class="tabl">
                <table class="table">

                <thead>
                    <tr>
                        <th>Id_pedido</th>
                        <th>Tipo</th>
                        <th>Nombre en el sueter</th>
                        <th>Imagen</th>
                        <th>Posición del logo</th>
                        <th>Talla</th>
                        <th>Color</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        
                        try{
                            $filas -= 1;
                            while($filas > -1){
                    ?>
                    <tr>
                        <th><?php echo $pedidos[$filas]['ID_PEDIDO']?></th>
                        <th><?php echo $pedidos[$filas]['TIPO_SUETER'] ?></th>
                        <th><?php echo $pedidos[$filas]['NOMBRE'] ?></th>
                        <th><img src="<?php echo("../../Imag_Pedidos/{$pedidos[$filas]['LOGO_ARTE']}") ?>" alt=""></th>
                        <th><?php echo $pedidos[$filas]['POSI_LOGO'] ?></th>
                        <th><?php echo $pedidos[$filas]['TALLA'] ?></th>
                        <th><?php echo $pedidos[$filas]['color'] ?></th>
                        <th><?php echo $pedidos[$filas]['ESTADO'] ?></th>
                        <th>
                            <?php if ($pedidos[$filas]['ESTADO'] == "Por procesar"){ ?>
                                <form action="" method="get">
                                <button type="submit" name="btpagar" value="<?php echo $pedidos[$filas]['ID_PEDIDO']?>">Pagar</button>
                                </form>
                                <form action="" method="get">
                                <button type="submit" name="btinsumos" value="<?php echo $pedidos[$filas]['ID_PEDIDO']?>">Falta Insumos</button>
                                </form>
                            <?php }else if ($pedidos[$filas]['ESTADO'] == "Falta Insumo") {?>
                                <form action="" method="get">
                                <button type="submit" name="btpagar" value="<?php echo $pedidos[$filas]['ID_PEDIDO']?>">Pagar</button>
                                </form>
                            <?php }else if ($pedidos[$filas]['ESTADO'] == "APROBADO") {?>
                                <form action="" method="get">
                                <button type="submit" name="btlisto" value="<?php echo $pedidos[$filas]['ID_PEDIDO']?>">Listo</button>
                                </form>
                            
                            <?php } else if($pedidos[$filas]['ESTADO'] == "RECOGER, LISTO"){echo("TERMINADO");} else {
                                echo("Pedido en cola");
                            }?>
                                
                            
                        </th>
                    </tr>
                    <?php $filas -= 1; }
                        } catch (Error $e){
                            echo("No tiene pedidos");
                        }
                    ?>

                </tbody>

                </table>
                </div>
                
            </section>
        </main>

        <footer>
            <h2 class="titulo-final">Copyright &copy; 2021 | Bordados S.A. Todos los derechos reservados.</h2>
        </footer>
    </body>
</html>