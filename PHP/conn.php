<?php
    $dsn = 'mysql:host=localhost;dbname=campanha;charset=utf8';
    $username = 'root';
    $password = '99771431';

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }