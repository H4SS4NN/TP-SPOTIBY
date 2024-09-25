

<div id="genericModal" class="fixed inset-0 z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Confirmer l'action</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="modal-message">Êtes-vous sûr de vouloir continuer cette action ?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="modal-form" method="POST">
                    <input type="hidden" name="album_id" id="modal-album-id">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirmer
                    </button>
                </form>
                <button onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>


<div id="iframeModal" class="fixed inset-0 z-20 hidden" aria-labelledby="iframe-modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-20 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-full max-w-3xl">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="iframe-modal-title">Vidéo</h3>
                <button onclick="closeIframeModal()" class="text-gray-500 hover:text-gray-700">
                    &times;
                </button>
            </div>
            <div class="p-4">
                <div id="iframeContent" class="w-full">
                   
                </div>
            </div>
        </div>
    </div>
</div>


<script>


function openModal(id, title, formAction, actionType) {
    // Afficher le modal
    document.getElementById('genericModal').classList.remove('hidden');

    // Changer le titre et le message du modal en fonction du type d'action
    if (actionType === 'delete') {
        document.getElementById('modal-title').innerText = "Supprimer " + title;
        document.getElementById('modal-message').innerText = "Êtes-vous sûr de vouloir supprimer '" + title + "' de vos favoris ?";
        document.getElementById('modal-album-id').value = id;  // Utilisé pour l'action de suppression
    } else if (actionType === 'logout') {
        document.getElementById('modal-title').innerText = "Déconnexion";
        document.getElementById('modal-message').innerText = "Êtes-vous sûr de vouloir vous déconnecter ?";
        document.getElementById('modal-album-id').value = '';  // Pas nécessaire pour la déconnexion
    } else if (actionType === 'add') {
        document.getElementById('modal-title').innerText = "Ajouter aux Favoris";
        document.getElementById('modal-message').innerText = "Êtes-vous sûr de vouloir ajouter cet album à vos favoris ?";
        document.getElementById('modal-album-id').value = id;  // Utilisé pour l'ajout aux favoris
    }

    // Configurer l'action du formulaire
    document.getElementById('modal-form').action = formAction;
    document.getElementById('modal-form').dataset.actionType = actionType;
}


function closeModal() {
    document.getElementById('genericModal').classList.add('hidden');
}


document.getElementById('modal-form').addEventListener('submit', function (event) {
    const actionType = event.target.dataset.actionType;
    const albumId = document.getElementById('modal-album-id').value;

    if (actionType === 'delete') {
        
        console.log(`Suppression de l'album avec l'ID: ${albumId}`);
    } else if (actionType === 'logout') {

        window.location.href = '../deconnexion/logout.php';
        event.preventDefault();  
    } else if (actionType === 'add') {

        event.preventDefault();  
        addToFavorites(albumId);
        closeModal(); 
    }
});


function openIframeModal(encodedIframe) {
    const iframeHTML = decodeURIComponent(encodedIframe);
    const iframeContent = document.getElementById('iframeContent');
    iframeContent.innerHTML = iframeHTML;
    
    const modal = document.getElementById('iframeModal');
    modal.classList.remove('hidden');
}


function closeIframeModal() {
    const modal = document.getElementById('iframeModal');
    modal.classList.add('hidden');
    
    const iframeContent = document.getElementById('iframeContent');
    iframeContent.innerHTML = ''; 
}


window.onclick = function(event) {
    const genericModal = document.getElementById('genericModal');
    if (event.target == genericModal) {
        closeModal();
    }

    const iframeModal = document.getElementById('iframeModal');
    if (event.target == iframeModal) {
        closeIframeModal();
    }
}


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
        alert(data);
    })
    .catch(error => console.error('Erreur lors de l\'ajout aux favoris', error));
}


</script>
