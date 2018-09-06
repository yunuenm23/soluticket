<?php

$servername = 'localhost';
$username = 'losfumancheros_admin';
$password = 'taquito123456';

try {
    $db = new PDO("mysql:host=$servername;dbname=losfumancheros_database", $username, $password);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: '.$e->getMessage();
}
