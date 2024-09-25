<?php
session_start();
$isUserLoggedIn = isset($_SESSION['user']); 
$userName = $isUserLoggedIn ? $_SESSION['user'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Application de Musique</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
body {
    background: linear-gradient(to bottom, black, #22C55D);
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
</style>
<body class="bg-black text-gray-900 font-sans">
    <div class="container mx-auto p-6">
        <?php if ($isUserLoggedIn): ?>
            <div class="rounded-lg shadow-lg p-6 mb-8 flex justify-between items-center bg-green-500 ">
                <h1 class="text-xl font-semibold">Bonjour, <?php echo htmlspecialchars($userName); ?> !</h1>
                <div>
                    <a href="../favoris/fav.php" class="bg-black text-white font-bold py-2 px-4 rounded mr-2">Mes Favoris</a>
                    <button onclick="openModal(null, '', '../deconnexion/logout.php', 'logout')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Déconnexion
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <div id="customAlert" class="hidden fixed top-4 left-1/2 transform -translate-x-1/2 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 w-3/4 md:w-1/2 rounded-lg shadow-lg" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-yellow-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M8.257 3.099c.765-1.36 2.698-1.36 3.463 0l6.93 12.316c.75 1.334-.202 2.994-1.732 2.994H3.06c-1.53 0-2.482-1.66-1.732-2.994l6.93-12.316zM11 13v-2H9v2h2zm0-4V7H9v2h2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">Attention needed</p>
                    <p id="alertMessage" class="text-sm">L'album a été ajouté à vos favoris.</p>
                </div>
            </div>
        </div>

        <div class="bg-black rounded-lg shadow-lg p-6 mb-8">
            <input type="text" id="searchInput" onkeyup="filterAlbums()" placeholder="Rechercher un album..." class="mb-4 w-50 p-2" style="border-radius:20px">
            <div id="album-info" class="flex flex-col md:flex-row flex-wrap items-center"></div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let albums = []; 

        fetch('../data/album.json')
            .then(response => response.json())
            .then(data => {
                albums = data.albums; 
                displayAlbums(albums); 
            })
            .catch(error => console.error('Erreur lors du chargement du JSON', error));

       
        function displayAlbums(albums) {
            const albumSection = document.getElementById('album-info');
            let albumsHTML = albums.map(album => `
               <div class="md:w-1/3 p-4">
                   <div class="bg-gray-50 rounded-lg shadow-md p-4 flex items-center" style="position:relative">
                       <img src="${album.cover_url}" alt="Cover de l'album" class="w-20 h-20 rounded-lg shadow-md mr-4">
                       <div>
                           <h2 class="text-lg font-semibold">${album.title.fr}</h2>
                           <p class="text-gray-600"> ${album.release_date}</p>
                           <p class="text-gray-600"> ${album.tracks.length}</p>
                       </div>
                       <div style="position:absolute; right:0px" class="mt-4 flex flex-col gap-2 items-center content-end">
                           <button class="bg-green-500 hover:bg-black text-white font-bold py-2 px-4 rounded mr-2" data-id="${album.id}" onclick="addToFavorites(${album.id})">
                               <3
                           </button>
                           <button class="bg-black hover:bg-black text-white font-bold py-2 px-4 rounded" onclick="openIframeModal('${encodeURIComponent(album.linkiframe)}')">
                               ▶️
                           </button>
                       </div>
                   </div>
               </div>
            `).join('');
            albumSection.innerHTML = albumsHTML;
        }

        window.filterAlbums = function() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const filteredAlbums = albums.filter(album => {
                const title = album.title.fr.toLowerCase();
                const artist = album.artist.name.fr.toLowerCase();
                return title.includes(searchInput) || artist.includes(searchInput);
            });
            displayAlbums(filteredAlbums);
        }
    });

    function addToFavorites(albumId) {
        fetch('add_to_favorites.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'album_id': albumId
            })
        })
        .then(response => response.text())
        .then(data => {
            showCustomAlert(data);  
        })
        .catch(error => console.error('Erreur lors de l\'ajout aux favoris', error));
    }


    function showCustomAlert(message) {
        const alertElement = document.getElementById('customAlert');
        const alertMessageElement = document.getElementById('alertMessage');
        

        alertMessageElement.innerText = message;
        

        alertElement.classList.remove('hidden');
        
     
        setTimeout(() => {
            alertElement.classList.add('hidden');
        }, 3000);
    }

    
    function openIframeModal(encodedIframe) {
        const iframeHTML = decodeURIComponent(encodedIframe);
        const iframeContent = document.getElementById('iframeContent');
        iframeContent.innerHTML = iframeHTML;
        
        const modal = document.getElementById('iframeModal');
        modal.classList.remove('hidden');
    }


    function closeModal() {
        const modal = document.getElementById('iframeModal');
        modal.classList.add('hidden');
        
        const iframeContent = document.getElementById('iframeContent');
        iframeContent.innerHTML = ''; 
    }

    
    window.onclick = function(event) {
        const modal = document.getElementById('iframeModal');
        if (event.target == modal) {
            closeModal();
        }
    }
    </script>

    <?php include '../modal/modal.php'; ?>
</body>
</html>
