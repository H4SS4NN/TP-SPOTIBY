<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require ('../connexion/db.php'); 

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['album_id'])) {
        $albumId = $_POST['album_id'];
        $userId = $_SESSION['user_id']; 
   

        if (!$userId) {
            echo "Vous devez être connecté pour ajouter un favori.";
            exit;
        }

        
        $stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = :user_id AND album_id = :album_id");
        $stmt->execute([':user_id' => $userId, ':album_id' => $albumId]);
        $favorite = $stmt->fetch();

        if (!$favorite) {

            $stmt = $pdo->prepare("INSERT INTO favorites (user_id, album_id) VALUES (:user_id, :album_id)");
            $stmt->execute([':user_id' => $userId, ':album_id' => $albumId]);
            echo "L'album a été ajouté à vos favoris.";
        } else {
            echo "Cet album est déjà dans vos favoris.";
        }
    } else {
        echo "Requête invalide.";
    }
} catch (PDOException $e) {
    
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
?>
