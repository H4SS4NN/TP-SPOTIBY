<?php

session_start();
require '../connexion/db.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion/index.php");
    exit();
}

$userId = $_SESSION['user_id'];


$albumData = file_get_contents('../data/album.json');
$albums = json_decode($albumData, true)['albums']; 


$stmt = $pdo->prepare("SELECT album_id FROM favorites WHERE user_id = :user_id");
$stmt->execute([':user_id' => $userId]);
$favorites = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);


$favAlbums = array_filter($albums, function($album) use ($favorites) {
    return in_array($album['id'], $favorites);
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900 font-sans">

    <div class="container mx-auto p-6">
        <a href="../accueil/accueil.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 mr-2">Retour à l'accueil</a>
        <h1 class="text-2xl font-bold mt-4 mb-6">Mes Favoris</h1>

        <?php if (count($favAlbums) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($favAlbums as $album): ?>
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <img src="<?php echo htmlspecialchars($album['cover_url']); ?>" alt="Cover de l'album" class="w-full rounded-lg shadow-md mb-4">
                        <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($album['title']['fr']); ?></h2>
                        <p class="text-gray-600">Artiste : <?php echo htmlspecialchars($album['artist']['name']['fr']); ?></p>
                        <p class="text-gray-600">Date de sortie : <?php echo htmlspecialchars($album['release_date']); ?></p>
                        <p class="text-gray-600">Genre : <?php echo htmlspecialchars($album['genre']['fr']); ?></p>
                        <button onclick="openModal(<?php echo $album['id']; ?>, '<?php echo addslashes($album['title']['fr']); ?>', 'delete_from_favorites.php', 'delete')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4">
                            Supprimer des Favoris
                        </button>


                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600">Vous n'avez aucun album dans vos favoris.</p>
        <?php endif; ?>
    </div>

    
    <?php include '../modal/modal.php'; ?>

</body>
</html>
