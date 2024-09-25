<?php

$host = proccess.env.DB_HOST;
$dbname = proccess.env.DB_NAME;

$username = proccess.env.DB_USER;
$password = proccess.env.DB_PASS;

try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    echo "<script>console.log('Connexion réussie à la base de données');</script>";
} catch (PDOException $e) {
  
    echo "<script>console.error('Erreur de connexion à la base de données : " . $e->getMessage() . "');</script>";
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
