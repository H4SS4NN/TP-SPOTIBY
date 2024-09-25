<?php
session_start();
require '../connexion/db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['album_id'])) {
    $userId = $_SESSION['user_id'];
    $albumId = $_POST['album_id'];


    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = :user_id AND album_id = :album_id");
    $stmt->execute([':user_id' => $userId, ':album_id' => $albumId]);


    header("Location: ../favoris/fav.php");
    exit();
} else {

    header("Location: ../favoris/fav.php");
    exit();
}
?>
