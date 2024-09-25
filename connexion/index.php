<?php
require 'db.php'; 
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id']; 
        header("Location: ../accueil/accueil.php");
        exit();
    } else {
        $error_message = "Identifiants incorrects. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image : url('fondconnexion.jpg');
    
    background-position: center;
    background-repeat: no-repeat;
    background-color : black;
        }
        .back{
            background: linear-gradient(to bottom,#22C55D,black);
    background-size: cover;
    background-position: center;
        }



    </style>
</head>
<body>
    <div class="flex justify-center items-center h-screen">
        <div  class="w-1/3 back p-8 " style="border-radius:20px;">
            <div class="flex items-center justify-between ">
               

            <h2 class="text-2xl  text-white font-bold mb-4">Connexion</h2>
            <img width="100px" src="../data/logo-removebg-preview.png" alt="">

            </div>
           

            <?php if (isset($error_message)): ?>
                <div class="mb-4 text-red-500"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-white text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-white text-sm font-bold mb-2">Mot de passe</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-black hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Connexion</button>
                </div>
            </form>
            <div class="mt-4 text-sm text-center">
                <a href="../accueil/signup.php" class="text-white hover:underline">Vous n'avez pas de compte ? Inscrivez-vous ici</a>
            </div>
        </div>
    </div>
</body>
</html>
