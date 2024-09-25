<?php
require '../connexion/db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<style>
    .back{
            background: linear-gradient(to bottom,#22C55D,black);
    background-size: cover;
    background-position: center;
        }
        body {
            background-color:black;
        }

    </style>
<body>


    <div class="flex justify-center items-center h-screen">
        <div class="w-1/3 back p-8   shadow-md"  style="border-radius:20px;">
            <h2 class="text-2xl font-bold mb-4">Signup</h2>
            <form method="POST"> 
                <div class="mb-4">
                    <label for="username" class="block text-white text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-white text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-white text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-white text-sm font-bold mb-2">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Signup</button>
                </div>
            </form>
            <div class="mt-4 text-sm text-center">
                <a href="../connexion/index.php" class="text-white hover:underline">Already have an account? Login here</a>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             
                echo "<script>console.log('Données reçues du formulaire.');</script>";
                
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                try {
                    
                    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
                    $stmt = $pdo->prepare($sql);

                
                    $stmt->execute([
                        ':username' => $username,
                        ':email' => $email,
                        ':password' => $password
                    ]);

                 
                    if ($stmt->rowCount() > 0) {
                        echo "<script>console.log('Utilisateur enregistré avec succès !');</script>";
                        echo "Utilisateur enregistré avec succès !";
                    } else {
                        echo "<script>console.error('Aucune ligne insérée.');</script>";
                    }
                } catch (PDOException $e) {
                   
                    echo "<script>console.error('Erreur : " . addslashes($e->getMessage()) . "');</script>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
