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

            if($user['PUESTO'] != 1){
                echo '<script language="javascript">alert("No tiene el permiso para estar aquí!");</script>';
                header('Location: ../../index.php');
            }

        }
        

    } else {
        echo '<script language="javascript">alert("No tiene el permiso para estar aquí!");</script>';
        header('Location: ../../index.php');
    }


    //Seccion para traer menu
    $sql = "SELECT * FROM TB_MENU WHERE ESTADO_PLATO = 'Activo'";
    $datos = $connet -> prepare($sql);
    $datos -> execute();
    $menu = $datos -> fetchall(PDO::FETCH_ASSOC);

    $NElementos = count($menu);


    function clear(){
        //Seccion entrada
        $_SESSION['Entradas'] = [];
        $_SESSION['EntraCant'] = [];
        //Seccion Platos Fuertes
        $_SESSION['Fuertes'] = [];
        $_SESSION['FuertCant'] = [];
        //Seccion Postres
        $_SESSION['Postre'] = [];
        $_SESSION['PostreCant'] = [];
        //Seccion Bebidas
        $_SESSION['Bebidas'] = [];
        $_SESSION['BebiCant'] = [];
    }
    

    //Seccion de logica para anadir y borrar elementos en listas de orden
    //////******Entradas
    //Aniadir
    if(isset($_POST['bt_entradass'])){
        if($_POST['MenuEntrada'] > 0){
            array_push($_SESSION['Entradas'],$_POST['MenuEntrada']);

            if($_POST['tb_entrada'] == "#"){
                array_push($_SESSION['EntraCant'],1);
            } else {
                array_push($_SESSION['EntraCant'],$_POST['tb_entrada']);
            }
        } 
    }
    //borrar elemento
    if(isset($_POST['bt_borrar_entrada'])){
        $variableA = [];
        $variableB = [];
        $i = 0;
        while($i < count($_SESSION['Entradas'])){
            if($i != $_POST['bt_borrar_entrada']){
                array_push($variableA, $_SESSION['Entradas'][$i]);
                array_push($variableB, $_SESSION['EntraCant'][$i]);
            }
            $i += 1;
        }
        $_SESSION['Entradas'] = $variableA;
        $_SESSION['EntraCant'] = $variableB;
        /*
        unset($_SESSION['Entradas'][$_POST['bt_borrar_entrada']]);
        unset($_SESSION['EntraCant'][$_POST['bt_borrar_entrada']]);*/
    }
    //////******Platos Fuertes
    //Aniadir
    if(isset($_POST['bt_fuerte'])){
        if($_POST['MenuFuerte'] > 0){
            array_push($_SESSION['Fuertes'],$_POST['MenuFuerte']);

            if($_POST['tb_fuerte'] == "#"){
                array_push($_SESSION['FuertCant'],1);
            } else {
                array_push($_SESSION['FuertCant'],$_POST['tb_fuerte']);
            }
        } 
    }
    //Borrar
    if(isset($_POST['bt_borrar_fuerte'])){
        $variableA = [];
        $variableB = [];
        $i = 0;
        while($i < count($_SESSION['Fuertes'])){
            if($i != $_POST['bt_borrar_fuerte']){
                array_push($variableA, $_SESSION['Fuertes'][$i]);
                array_push($variableB, $_SESSION['FuertCant'][$i]);
            }
            $i += 1;
        }
        $_SESSION['Fuertes'] = $variableA;
        $_SESSION['FuertCant'] = $variableB;
        /*
        unset($_SESSION['Fuertes'][$_POST['bt_borrar_fuerte']]);
        unset($_SESSION['FuertCant'][$_POST['bt_borrar_fuerte']]);*/
    }

    //////******Postres
    //Aniadir
    if(isset($_POST['bt_postre'])){
        if($_POST['MenuPostre'] > 0){
            array_push($_SESSION['Postre'],$_POST['MenuPostre']);

            if($_POST['tb_postre'] == "#"){
                array_push($_SESSION['PostreCant'],1);
            } else {
                array_push($_SESSION['PostreCant'],$_POST['tb_postre']);
            }
        } 
    }
    //Borrar
    if(isset($_POST['bt_borrar_postre'])){
        $variableA = [];
        $variableB = [];
        $i = 0;
        while($i < count($_SESSION['Postre'])){
            if($i != $_POST['bt_borrar_postre']){
                array_push($variableA, $_SESSION['Postre'][$i]);
                array_push($variableB, $_SESSION['PostreCant'][$i]);
            }
            $i += 1;
        }
        $_SESSION['Postre'] = $variableA;
        $_SESSION['PostreCant'] = $variableB;
        /*
        unset($_SESSION['Postre'][$_POST['bt_borrar_postre']]);
        unset($_SESSION['PostreCant'][$_POST['bt_borrar_postre']]);*/
    }

    //////******Bebidas
    //Aniadir
    if(isset($_POST['bt_bebi'])){
        if($_POST['MenuBebi'] > 0){
            array_push($_SESSION['Bebidas'],$_POST['MenuBebi']);

            if($_POST['tb_bebi'] == "#"){
                array_push($_SESSION['BebiCant'],1);
            } else {
                array_push($_SESSION['BebiCant'],$_POST['tb_bebi']);
            }
        } 
    }
    //Borrar
    if(isset($_POST['bt_borrar_bebi'])){
        $variableA = [];
        $variableB = [];
        $i = 0;
        while($i < count($_SESSION['Bebidas'])){
            if($i != $_POST['bt_borrar_bebi']){
                array_push($variableA, $_SESSION['Bebidas'][$i]);
                array_push($variableB, $_SESSION['BebiCant'][$i]);
            }
            $i += 1;
        }
        $_SESSION['Bebidas'] = $variableA;
        $_SESSION['BebiCant'] = $variableB;

    }

    //Programacion de boton cancelar
    if(isset($_POST['bt_cancel'])){
        clear();
        header("Location: PanelPrincipal.php");
    }
    
    //Envio del orden a la base de datos
    if(isset($_POST['bt_enviar'])){
        //Realizacion de la orden
        if(!empty($_POST['nclien']) && !empty($_POST['nmesa'])){
            $TotalCuenta = 0;
            //Insertando la orden
            
            $sql = "INSERT INTO TB_ORDEN (NOMBRE_CLIENTE,NUM_MESA,MONTO_CUENTA,ID_MESERO, ESTADO, FECHA) VALUES (:nome,:nmesa, :total,:nsal,'Preparando',CURDATE())";
            $datos = $connet -> prepare($sql);
            $datos -> bindParam(':nome',$_POST['nclien']);
            $datos -> bindParam(':nmesa',$_POST['nmesa']);
            $datos -> bindParam(':nsal',$_SESSION['user_id']);
            
            //sacando el total de la cuenta

            //Suma para las entradas
            if ($_SESSION['Entradas'] != NULL){
                $i = 0;
                while($i < count($_SESSION['Entradas'])){
                    
                    $j = 0;
                    while($j < count($menu)){
                        if($_SESSION['Entradas'][$i] == $menu[$j]['ID_PLATO']){
                            $TotalCuenta += $menu[$j]['PRECIO'] * $_SESSION['EntraCant'][$i];
                        }

                        $j += 1;
                    }

                    $i += 1;
                }
            }

            //Suma para los platos fuertes
            if ($_SESSION['Fuertes'] != NULL){
                $i = 0;
                while($i < count($_SESSION['Fuertes'])){
                    
                    $j = 0;
                    while($j < count($menu)){
                        if($_SESSION['Fuertes'][$i] == $menu[$j]['ID_PLATO']){
                            $TotalCuenta += $menu[$j]['PRECIO'] * $_SESSION['FuertCant'][$i];
                        }

                        $j += 1;
                    }

                    $i += 1;
                }
            }

            //Suma para las bebidas
            if ($_SESSION['Bebidas'] != NULL){
                $i = 0;
                while($i < count($_SESSION['Bebidas'])){
                    
                    $j = 0;
                    while($j < count($menu)){
                        if($_SESSION['Bebidas'][$i] == $menu[$j]['ID_PLATO']){
                            $TotalCuenta += $menu[$j]['PRECIO'] * $_SESSION['BebiCant'][$i];
                        }

                        $j += 1;
                    }

                    $i += 1;
                }
            }

            //Suma para los postres
            if ($_SESSION['Postre'] != NULL){
                $i = 0;
                while($i < count($_SESSION['Postre'])){
                    
                    $j = 0;
                    while($j < count($menu)){
                        if($_SESSION['Postre'][$i] == $menu[$j]['ID_PLATO']){
                            $TotalCuenta += $menu[$j]['PRECIO'] * $_SESSION['PostreCant'][$i];
                        }

                        $j += 1;
                    }

                    $i += 1;
                }
            }


            $datos -> bindParam(':total', $TotalCuenta);
            
            //Insertando elementos de la orden
            try{
                $datos -> execute();
                
                
            } catch (error $e){
                echo($e);
            }

            //Extrayendo ID de la orden
            $sql = "SELECT * FROM TB_ORDEN WHERE ID_MESERO = :ide ORDER BY ID_ORDEN DESC ";
            $datos = $connet -> prepare($sql);
            $datos -> bindParam('ide', $_SESSION['user_id']);
            $datos -> execute();
            $regist = $datos -> fetch(PDO::FETCH_ASSOC);
            //Cargando id de la factura a variable
            $IdFact = $regist['ID_ORDEN'];
            
            //Insertando detalles de la orden a la tabla
            $Vector_Pedidos = [];
            //Entradas
            if ($_SESSION['Entradas'] != NULL){

                $i = 0;
                while($i < count($_SESSION['Entradas'])){
                    if($_SESSION['EntraCant'][$i] > 1){
                        $j = 0;
                        while($j < $_SESSION['EntraCant'][$i]){
                            array_push($Vector_Pedidos,$_SESSION['Entradas'][$i]);
                            $j += 1;
                        }
                    }else{
                        array_push($Vector_Pedidos,$_SESSION['Entradas'][$i]);
                    }
                    $i += 1;
                }
            }

            //adicionde los platos fuertes
            if ($_SESSION['Fuertes'] != NULL){
                $i = 0;
                while($i < count($_SESSION['Fuertes'])){
                    
                    $j = 0;
                    if($_SESSION['FuertCant'][$i] > 1){
                        $j = 0;
                        while($j < $_SESSION['FuertCant'][$i]){
                            array_push($Vector_Pedidos,$_SESSION['Fuertes'][$i]);
                            $j += 1;
                        }
                    } else {
                        array_push($Vector_Pedidos,$_SESSION['Fuertes'][$i]);
                    }

                    $i += 1;
                }
            }

            //adicion de las bebidas
            if ($_SESSION['Bebidas'] != NULL){
                $i = 0;
                while($i < count($_SESSION['Bebidas'])){
                    
                    $j = 0;
                    if($_SESSION['BebiCant'][$i] > 1){
                        $j = 0;
                        while($j < $_SESSION['BebiCant'][$i]){
                            array_push($Vector_Pedidos,$_SESSION['Bebidas'][$i]);
                            $j += 1;
                        }
                    } else {
                        array_push($Vector_Pedidos,$_SESSION['Bebidas'][$i]);
                    }

                    $i += 1;
                }
            }

            //adicion de los postres
            if ($_SESSION['Postre'] != NULL){
                $i = 0;
                while($i < count($_SESSION['Postre'])){
                    
                    $j = 0;
                    if($_SESSION['PostreCant'][$i] > 1){
                        $j = 0;
                        while($j < $_SESSION['PostreCant'][$i]){
                            array_push($Vector_Pedidos,$_SESSION['Postre'][$i]);
                            $j += 1;
                        }
                    }else{
                        array_push($Vector_Pedidos,$_SESSION['Postre'][$i]);
                    }

                    $i += 1;
                }
            }
            
            //Insercion

            try{

                $i = 0;
                while ($i < count($Vector_Pedidos)){
                    $sql = "INSERT INTO TB_DETALLE_ORDEN (ID_ORDEN,ID_PLATO,DETALLE_PLATO) VALUES (:NUMOR, :NUMPLA, 'VACIO')";
                    $datos = $connet -> prepare($sql);
                    $datos -> bindParam(':NUMOR',$IdFact);
                    $datos -> bindParam(':NUMPLA', $Vector_Pedidos[$i]);
                    $datos -> execute();

                    $i += 1;
                }
                
                header("Location: PanelPrincipal.php");

            } catch (Error $e){
                echo($e);
            }


        } else {
            echo '<script language="javascript">alert("Nombre del cliente o numero de mesa vacio!");</script>';
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
           <form action="" method="post">
           <div class="TituloForm">
               <h2>Nueva Orden</h2>
           </div>

           <div class="NuevaOrden">
                
            <div class="PregOrden">
                <h4>Nombre cliente:</h4>
                <input type="text" name="nclien" placeholder="Elsa Pito">
            </div>
            <div class="PregOrden">
                <h4>Numero de mesa:</h4>
                <input type="text" name="nmesa" placeholder="45">
            </div>
            
            
                <div class="TablaOrden">
                    <h4>Entradas</h4>

                    
                    <?php
                        if($_SESSION['Entradas'] == NULL){
                            $_SESSION['Entradas'] = [];
                            $_SESSION['EntraCant'] = [];
                            //
                            ?>
                            <div class="marco">
                                
                                    
                                    <div class="Item">
                                        <select name="MenuEntrada" >
                                                <option value="0" selected> -- </option>
                                            <?php
                                                $elemento = 0;
                                                while ($elemento < $NElementos){

                                                    if ($menu[$elemento]['TIPO_PLATO'] == 1){
                                                        ?>
                                                            <option value="<?php echo($menu[$elemento]['ID_PLATO']); ?>"><?php echo($menu[$elemento]['NOMBRE_PLATO']);?></option>
                                                        <?php
                                                    }

                                                    $elemento += 1;
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="item">
                                        <input type="text" value="#" name="tb_entrada" class="tb_cantidad">
                                    </div>
                                    <div class="item">
                                        <input type="submit" value="OK" name="bt_entradass" class="boton_ok">
                                    </div>
                            </div>

                            <?php
                            //Seccion para lista de elemento y adicionales
                        } else {
                            $NCiclo = count($_SESSION['Entradas']);
                            $Ciclo = 0;
                            while ($Ciclo < $NCiclo){
                                // Imprecion de las ordenes ya introducidas
                                ?>

                                <div class="marcoo">
                                
                                    
                                    <div class="Item">
                                        <select name="MenuEntrada" >
                                                <option value="<?php echo($Ciclo); ?>" selected> <?php 
                                                //Para buscar el nombre del elemento
                                                $nme = 0;
                                                while ($nme < $NElementos){
                                                    if($menu[$nme]['ID_PLATO'] == $_SESSION['Entradas'][$Ciclo]){
                                                        echo($menu[$nme]['NOMBRE_PLATO']);
                                                        break;
                                                    }
                                                    $nme += 1;
                                                }
                                                ?> </option>
                                            
                                        </select>
                                    </div>
                                    <div class="item">
                                        <input type="text" value="<?php echo($_SESSION['EntraCant'][$Ciclo]); ?>" name="tb_entrada" class="tb_cantidad" readonly>
                                    </div>
                                    <div class="item">
                                        <button type="submit" name="bt_borrar_entrada" value="<?php echo($Ciclo);?>">X</button>
                                    </div>
                                </div>
                                
                                <?php
                                $Ciclo += 1;
                            }

                            ?>
                            <div class="marco">
                                
                                    
                                <div class="Item">
                                    <select name="MenuEntrada" >
                                            <option value="0" selected> -- </option>
                                        <?php
                                            $elemento = 0;
                                            while ($elemento < $NElementos){

                                                if ($menu[$elemento]['TIPO_PLATO'] == 1){
                                                    ?>
                                                        <option value="<?php echo($menu[$elemento]['ID_PLATO']); ?>"><?php echo($menu[$elemento]['NOMBRE_PLATO']);?></option>
                                                    <?php
                                                }

                                                $elemento += 1;
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="item">
                                    <input type="text" value="#" name="tb_entrada" class="tb_cantidad">
                                </div>
                                <div class="item">
                                    <input type="submit" value="OK" name="bt_entradass" class="boton_ok">
                                </div>
                             </div>
                            <?php

                        }
                    ?>

     
                </div>
                


                <div class="TablaOrden">
                    <h4>Platos Fuertes</h4>

                    
                    <?php
                        if($_SESSION['Fuertes'] == NULL){
                            $_SESSION['Fuertes'] = [];
                            $_SESSION['FuertCant'] = [];
                            //
                            ?>
                            <div class="marco">
                                
                                    
                                    <div class="Item">
                                        <select name="MenuFuerte" >
                                                <option value="0" selected> -- </option>
                                            <?php
                                                $elemento = 0;
                                                while ($elemento < $NElementos){

                                                    if ($menu[$elemento]['TIPO_PLATO'] == 2){
                                                        ?>
                                                            <option value="<?php echo($menu[$elemento]['ID_PLATO']); ?>"><?php echo($menu[$elemento]['NOMBRE_PLATO']);?></option>
                                                        <?php
                                                    }

                                                    $elemento += 1;
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="item">
                                        <input type="text" value="#" name="tb_fuerte" class="tb_cantidad">
                                    </div>
                                    <div class="item">
                                        <input type="submit" value="OK" name="bt_fuerte" class="boton_ok">
                                    </div>
                            </div>

                            <?php
                            //Seccion para lista de elemento y adicionales
                        } else {
                            $NCiclo = count($_SESSION['Fuertes']);
                            $Ciclo = 0;
                            while ($Ciclo < $NCiclo){
                                // Imprecion de las ordenes ya introducidas
                                ?>

                                <div class="marcoo">
                                
                                    
                                    <div class="Item">
                                        <select name="MenuFuerte" >
                                                <option value="<?php echo($Ciclo); ?>" selected> <?php 
                                                //Para buscar el nombre del elemento
                                                $nme = 0;
                                                while ($nme < $NElementos){
                                                    if($menu[$nme]['ID_PLATO'] == $_SESSION['Fuertes'][$Ciclo]){
                                                        echo($menu[$nme]['NOMBRE_PLATO']);
                                                        break;
                                                    }
                                                    $nme += 1;
                                                }
                                                ?> </option>
                                            
                                        </select>
                                    </div>
                                    <div class="item">
                                        <input type="text" value="<?php echo($_SESSION['FuertCant'][$Ciclo]); ?>" name="tb_fuerte" class="tb_cantidad" readonly>
                                    </div>
                                    <div class="item">
                                        <button type="submit" name="bt_borrar_fuerte" value="<?php echo($Ciclo);?>">X</button>
                                    </div>
                                </div>
                                
                                <?php
                                $Ciclo += 1;
                            }

                            ?>
                            <div class="marco">
                                
                                    
                                <div class="Item">
                                    <select name="MenuFuerte" >
                                            <option value="0" selected> -- </option>
                                        <?php
                                            $elemento = 0;
                                            while ($elemento < $NElementos){

                                                if ($menu[$elemento]['TIPO_PLATO'] == 2){
                                                    ?>
                                                        <option value="<?php echo($menu[$elemento]['ID_PLATO']); ?>"><?php echo($menu[$elemento]['NOMBRE_PLATO']);?></option>
                                                    <?php
                                                }

                                                $elemento += 1;
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="item">
                                    <input type="text" value="#" name="tb_fuerte" class="tb_cantidad">
                                </div>
                                <div class="item">
                                    <input type="submit" value="OK" name="bt_fuerte" class="boton_ok">
                                </div>
                             </div>
                            <?php

                        }
                    ?>

     
                </div>


                <div class="TablaOrden">
                    <h4>Bebidas</h4>

                    
                    <?php
                        if($_SESSION['Bebidas'] == NULL){
                            $_SESSION['Bebidas'] = [];
                            $_SESSION['BebiCant'] = [];
                            //
                            ?>
                            <div class="marco">
                                
                                    
                                    <div class="Item">
                                        <select name="MenuBebi" >
                                                <option value="0" selected> -- </option>
                                            <?php
                                                $elemento = 0;
                                                while ($elemento < $NElementos){

                                                    if ($menu[$elemento]['TIPO_PLATO'] == 4){
                                                        ?>
                                                            <option value="<?php echo($menu[$elemento]['ID_PLATO']); ?>"><?php echo($menu[$elemento]['NOMBRE_PLATO']);?></option>
                                                        <?php
                                                    }

                                                    $elemento += 1;
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="item">
                                        <input type="text" value="#" name="tb_bebi" class="tb_cantidad">
                                    </div>
                                    <div class="item">
                                        <input type="submit" value="OK" name="bt_bebi" class="boton_ok">
                                    </div>
                            </div>

                            <?php
                            //Seccion para lista de elemento y adicionales
                        } else {
                            $NCiclo = count($_SESSION['Bebidas']);
                            $Ciclo = 0;
                            while ($Ciclo < $NCiclo){
                                // Imprecion de las ordenes ya introducidas
                                ?>

                                <div class="marcoo">
                                
                                    
                                    <div class="Item">
                                        <select name="MenuBebi" >
                                                <option value="<?php echo($Ciclo); ?>" selected> <?php 
                                                //Para buscar el nombre del elemento
                                                $nme = 0;
                                                while ($nme < $NElementos){
                                                    if($menu[$nme]['ID_PLATO'] == $_SESSION['Bebidas'][$Ciclo]){
                                                        echo($menu[$nme]['NOMBRE_PLATO']);
                                                        break;
                                                    }
                                                    $nme += 1;
                                                }
                                                ?> </option>
                                            
                                        </select>
                                    </div>
                                    <div class="item">
                                        <input type="text" value="<?php echo($_SESSION['BebiCant'][$Ciclo]); ?>" name="tb_bebi" class="tb_cantidad" readonly>
                                    </div>
                                    <div class="item">
                                        <button type="submit" name="bt_borrar_bebi" value="<?php echo($Ciclo);?>">X</button>
                                    </div>
                                </div>
                                
                                <?php
                                $Ciclo += 1;
                            }

                            ?>
                            <div class="marco">
                                
                                    
                                <div class="Item">
                                    <select name="MenuBebi" >
                                            <option value="0" selected> -- </option>
                                        <?php
                                            $elemento = 0;
                                            while ($elemento < $NElementos){

                                                if ($menu[$elemento]['TIPO_PLATO'] == 4){
                                                    ?>
                                                        <option value="<?php echo($menu[$elemento]['ID_PLATO']); ?>"><?php echo($menu[$elemento]['NOMBRE_PLATO']);?></option>
                                                    <?php
                                                }

                                                $elemento += 1;
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="item">
                                    <input type="text" value="#" name="tb_bebi" class="tb_cantidad">
                                </div>
                                <div class="item">
                                    <input type="submit" value="OK" name="bt_bebi" class="boton_ok">
                                </div>
                             </div>
                            <?php

                        }
                    ?>

     
                </div>

                <div class="TablaOrden">
                    <h4>Postres</h4>

                    
                    <?php
                        if($_SESSION['Postre'] == NULL){
                            $_SESSION['Postre'] = [];
                            $_SESSION['PostreCant'] = [];
                            //
                            ?>
                            <div class="marco">
                                
                                    
                                    <div class="Item">
                                        <select name="MenuPostre" >
                                                <option value="0" selected> -- </option>
                                            <?php
                                                $elemento = 0;
                                                while ($elemento < $NElementos){

                                                    if ($menu[$elemento]['TIPO_PLATO'] == 3){
                                                        ?>
                                                            <option value="<?php echo($menu[$elemento]['ID_PLATO']); ?>"><?php echo($menu[$elemento]['NOMBRE_PLATO']);?></option>
                                                        <?php
                                                    }

                                                    $elemento += 1;
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="item">
                                        <input type="text" value="#" name="tb_postre" class="tb_cantidad">
                                    </div>
                                    <div class="item">
                                        <input type="submit" value="OK" name="bt_postre" class="boton_ok">
                                    </div>
                            </div>

                            <?php
                            //Seccion para lista de elemento y adicionales
                        } else {
                            $NCiclo = count($_SESSION['Postre']);
                            $Ciclo = 0;
                            while ($Ciclo < $NCiclo){
                                // Imprecion de las ordenes ya introducidas
                                ?>

                                <div class="marcoo">
                                
                                    
                                    <div class="Item">
                                        <select name="MenuPostre" >
                                                <option value="<?php echo($Ciclo); ?>" selected> <?php 
                                                //Para buscar el nombre del elemento
                                                $nme = 0;
                                                while ($nme < $NElementos){
                                                    if($menu[$nme]['ID_PLATO'] == $_SESSION['Postre'][$Ciclo]){
                                                        echo($menu[$nme]['NOMBRE_PLATO']);
                                                        break;
                                                    }
                                                    $nme += 1;
                                                }
                                                ?> </option>
                                            
                                        </select>
                                    </div>
                                    <div class="item">
                                        <input type="text" value="<?php echo($_SESSION['PostreCant'][$Ciclo]); ?>" name="tb_postre" class="tb_cantidad" readonly>
                                    </div>
                                    <div class="item">
                                        <button type="submit" name="bt_borrar_postre" value="<?php echo($Ciclo);?>">X</button>
                                    </div>
                                </div>
                                
                                <?php
                                $Ciclo += 1;
                            }

                            ?>
                            <div class="marco">
                                
                                    
                                <div class="Item">
                                    <select name="MenuPostre" >
                                            <option value="0" selected> -- </option>
                                        <?php
                                            $elemento = 0;
                                            while ($elemento < $NElementos){

                                                if ($menu[$elemento]['TIPO_PLATO'] == 3){
                                                    ?>
                                                        <option value="<?php echo($menu[$elemento]['ID_PLATO']); ?>"><?php echo($menu[$elemento]['NOMBRE_PLATO']);?></option>
                                                    <?php
                                                }

                                                $elemento += 1;
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="item">
                                    <input type="text" value="#" name="tb_postre" class="tb_cantidad">
                                </div>
                                <div class="item">
                                    <input type="submit" value="OK" name="bt_postre" class="boton_ok">
                                </div>
                             </div>
                            <?php

                        }
                    ?>

     
                </div>
    

                
                <input type="submit" value="Enviar" name="bt_enviar" class="boton_login">
                <input type="submit" value="Cancelar" name="bt_cancel" class="boton_login">
                
           </div>
           </form>

       </div> 

    </main>

    
</body>
</html>