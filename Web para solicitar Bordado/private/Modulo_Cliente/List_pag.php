<?php

    session_start();
    require '../../BD_conex.php';
    
    if(isset($_SESSION['user_id'])){
        $sql = "SELECT * FROM tb_pago WHERE ID_CLIENTE = :id";
        $datos = $connet -> prepare($sql);
        $datos -> bindParam(':id', $_SESSION['user_id']);
        $datos -> execute();
        $registros = $datos->fetchAll(PDO::FETCH_ASSOC);
        $filas = $datos -> fetchColumn();
        $user = null;
        
        try{
            if(count($registros) > 0){
                            $pedidos = $registros;
                            $filas = count($pedidos);
            }
        } catch(Error $e){
            $filas = 0;
        }

    } else {
        echo '<script language="javascript">alert("Accedo denegado, Inicie sesión!");</script>';
        header('Location: /Proyect_IS2');
    }

    if (isset($_POST['btpagar'])){
        $ruta = "../../Img_pag/{$_FILES['comprob']['name']}";
        $imag = $_FILES['comprob']['tmp_name'];

        $sql = "UPDATE tb_pago SET METODO = :met, COMPROBANTE = :dir, ESTADO = 'Comprobando' WHERE ID_PEDIDO = :pedi";
        $actuali = $connet -> prepare($sql);
        $actuali -> bindParam(':met', $_POST['metodo']);
        $actuali -> bindParam(':dir', $_FILES['comprob']['name']);
        $actuali -> bindParam(':pedi', $_POST['btpagar']);

        if($actuali ->execute()){
            move_uploaded_file($imag, $ruta);
            $sql = "UPDATE tb_pedido SET ESTADO = 'Comprobando' WHERE ID_PEDIDO = :idp";
            $actuali = $connet -> prepare($sql);
            $actuali -> bindParam(':idp', $_POST['btpagar']);
            $actuali -> execute();
            header('Location: List_pag.php');
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
                <div>
                    <h4 class = "detalle_info">Por el momento solo tenemos disponible el metodo de transferencia. <br>
                    Esta esta sujeta a verificación, como metodo de seguridad para evitar fraude. <br>
                    Puede realizar la transferencia a nuestra cuenta de tipo ahorro con numero 00-00-0000-0 del banco XXXXXXXXXXXX. <br>
                    Debe adjuntar el comprobante de transaccion. <br>
                    Monto de las transferencias: <br>
                    &nbsp;&nbsp;&nbsp; -Polo tiene un valor de 15.00$. <br>
                    &nbsp;&nbsp;&nbsp; -Cuello tortuga tiene un valor de 10.00$.
                    </h4><br><br><br>
                </div>
                <div class="tabl">
                    <table class="table">

                        <thead>
                            <tr>
                                <th>Id pedido</th>
                                <th>Estado</th>
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
                                    <form action="List_pag.php" method="POST" enctype="multipart/form-data">
                                    <th><?php echo $pedidos[$filas]['ID_PEDIDO']?></th>
                                    <th><?php echo $pedidos[$filas]['ESTADO'] ?></th>

                                    <?php if($pedidos[$filas]['METODO'] == ""){?>
                                        <th>
                                            <select name="metodo" required>
                                            <option value=""></option>
                                            <option value="transferencia">Transferencia</option>
                                            </select>
                                        </th>
                                        <th>
                                            <input type="file" name="comprob" required>
                                        </th>

                                    <?php } else {?>
                                        
                                        <th>
                                           <?php echo ($pedidos[$filas]['METODO']); ?>
                                        </th>
                                        <th>
                                            <img src="<?php echo("../../Img_pag/{$pedidos[$filas]['COMPROBANTE']}"); ?>" alt="">
                                        </th>

                                    <?php }?>
                                    <th>
                                        <?php if ($pedidos[$filas]['ESTADO'] == "Por pagar"){ ?>
                                            
                                            <button type="submit" name="btpagar" value="<?php echo $pedidos[$filas]['ID_PEDIDO']?>">Pagar</button>
                                            
                                        <?php } else {
                                            echo("Esperando aprobación o en proceso");
                                        }?>
                                            
                                    </th>
                                    </form>
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