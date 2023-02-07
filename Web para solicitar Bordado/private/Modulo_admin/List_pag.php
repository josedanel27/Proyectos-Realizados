<?php

    session_start();
    require '../../BD_conex.php';
    
    if(isset($_SESSION['user_id'])){
        $sql = "SELECT * FROM tb_pago";
        $datos = $connet -> prepare($sql);
        $datos -> execute();
        $registros = $datos->fetchAll(PDO::FETCH_ASSOC);
        $filas = $datos -> fetchColumn();
        $user = null;
        
        
            if(count($registros) > 0){
                $pedidos = $registros;
                $filas = count($pedidos);
            }
        
    } else {
        echo '<script language="javascript">alert("Accedo denegado, Inicie sesión!");</script>';
        header('Location: /Proyect_IS2');
    }

    if(isset($_GET['btaprobar'])){
        $sql = "UPDATE tb_pedido SET ESTADO = 'APROBADO' WHERE ID_PEDIDO = :idp";
        $actuali = $connet -> prepare($sql);
        $actuali -> bindParam(':idp', $_GET['btaprobar']);

        if ($actuali -> execute()){
            $sql = "UPDATE tb_pago SET ESTADO = 'APROBADO' WHERE ID_PEDIDO = :idp";
            $actuali = $connet -> prepare($sql);
            $actuali -> bindParam(':idp', $_GET['btaprobar']);
            $actuali -> execute();
        }
    }

    if(isset($_GET['bterror'])){
        $sql = "UPDATE tb_pedido SET ESTADO = 'Error' WHERE ID_PEDIDO = :idp";
        $actuali = $connet -> prepare($sql);
        $actuali -> bindParam(':idp', $_GET['bterror']);

        if ($actuali -> execute()){
            $sql = "UPDATE tb_pago SET ESTADO = 'Error' WHERE ID_PEDIDO = :idp";
            $actuali = $connet -> prepare($sql);
            $actuali -> bindParam(':idp', $_GET['bterror']);
            $actuali -> execute();
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
                <a href="../Mod_Adm.php">INICIO</a>
                <a href="">PRODUCTOS</a>
                <a href=""></a>
                <a href="../../logout.php">CERRAR SESIÓN</a>
            </nav>

            <div class = "Texto_Inicio">
                <h1>Bienvenido a Bordados S.A.</h1>
                <h3>Nos dedicamos crear productos que te hagan sentir parte de tu asociacion o institución!</h3>
            </div>
        </header>
        

        <main>
            <section class="contenedor-tabla">
                <div><h2 class="titulo_list">Lista de pagos de pedido</h2></div>
                <div class="tabl">
                    <table class="table">

                        <thead>
                            <tr>
                                <th>Id_pedido</th>
                                <th>Estado</th>
                                <th>ID_Cliente</th>
                                <th>Metodo de pago</th>
                                <th>Comprobante</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            
                            <?php
                            
                            try{
                                
                                if($filas > 0){
                                $filas -= 1; 
                                  
                                while($filas > -1){
                                ?>
                                <tr>
                                    <th><?php echo $pedidos[$filas]['ID_PEDIDO']?></th>
                                    <th><?php echo $pedidos[$filas]['ESTADO'] ?></th>
                                    <th><?php echo $pedidos[$filas]['ID_Cliente'] ?></th>
                                    <th><?php echo $pedidos[$filas]['METODO'] ?></th>
                                    <?php if($pedidos[$filas]['COMPROBANTE'] != ""){?>
                                    <th><img src="<?php echo ("../../Img_pag/{$pedidos[$filas]['COMPROBANTE']}")?>" alt=""></th>
                                    <?php } else { ?>
                                        <th></th>
                                    <?php } ?>
                                    <th>
                                        <?php if ($pedidos[$filas]['ESTADO'] == "Comprobando"){ ?>
                                            <form action="" method="GET">
                                            <button type="submit" name="btaprobar" value="<?php echo $pedidos[$filas]['ID_PEDIDO']?>">Aprobar</button>
                                            </form>
                                            <form action="" method="GET">
                                            <button type="submit" name="bterror" value="<?php echo $pedidos[$filas]['ID_PEDIDO']?>">Error</button>
                                            </form>
                                        <?php } else if ($pedidos[$filas]['ESTADO'] == "Error") {?>
                                            <form action="" method="GET">
                                            <button type="submit" name="btaprobar" value="<?php echo $pedidos[$filas]['ID_PEDIDO']?>">Aprobar</button>
                                            </form>
                                        <?php } 
                                        else if($pedidos[$filas]['ESTADO'] == "APROBADO"){echo("En producción");}
                                        else {echo("Esperando pago");}?>
                                            
                                            
                                            
                                        
                                    </th>
                                </tr>
                            <?php $filas -= 1; } } else {?>
                            <tr>
                                <th>
                                    <?php echo("No tiene pedidos");?> 
                                </th>
                                <th>N/A</th>
                                <th>N/A</th>
                                <th>N/A</th>
                                <th>N/A</th>
                                <th>N/A</th>
                            </tr> 
                            <?php    
                            }} catch (Error $e){
                                    
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