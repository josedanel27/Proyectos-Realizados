<?php
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $DBname = 'bor_dados';

    try{
        $connet = new PDO("mysql:host=$server;dbname=$DBname;",$username,$password);
    }
    catch(PDOException $e){
        die('Connection Failed: ' .$e->getMessage());
    }
?>