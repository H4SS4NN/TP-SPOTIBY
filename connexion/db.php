<?php
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USERNAME') ;
$password = getenv('DB_PASSWORD') ;

try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    echo "<script>console.log('Connexion réussie à la base de données');</script>";
} catch (PDOException $e) {
  
    echo "<script>console.error('Erreur de connexion à la base de données : " . $e->getMessage() . "');</script>";
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
