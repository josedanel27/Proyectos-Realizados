<?php
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $DBname = 'Sistema_Pedidos';

    try{
        $connet = new PDO("mysql:host=$server;dbname=$DBname;",$username,$password);
    }
    catch(PDOException $e){
        die('Connection Failed: ' .$e->getMessage());
    }
?>