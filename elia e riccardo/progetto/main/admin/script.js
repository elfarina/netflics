const posterInput = document.getElementById('content-poster');
const posterNameInput = document.querySelector('.poster-name');
const movieInput = document.getElementById('content-movie');
const movieNameInput = document.querySelector('.movie-name');
const form = document.getElementById('uploadForm');
const progressBar = document.getElementById('progressBar');
const uploadStatus = document.getElementById('uploadStatus');
const errorMessagesDiv = document.getElementById('errorMessages'); // Div per visualizzare gli errori

posterInput.addEventListener('change', () => {
    const fileName = posterInput.files[0]?.name || 'Nessun file selezionato';
    posterNameInput.textContent = fileName;
});

movieInput.addEventListener('change', () => {
    const fileName = movieInput.files[0]?.name || 'Nessun file selezionato';
    movieNameInput.textContent = fileName;
});

form.addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(form);
    const title = document.getElementById('content-title').value.trim();
    const xhr = new XMLHttpRequest();

    xhr.open('POST', form.action, true);
    xhr.upload.addEventListener('progress', function (e) {
        if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percentComplete + '%';
            progressBar.textContent = percentComplete + '%';
        }
    });

    // Quando il caricamento è completo
    xhr.addEventListener('load', function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);

            // Se ci sono errori, mostriamoli
            if (response.status === 'error') {
                displayErrors(response.errors); // Mostra gli errori se ci sono
            } else {
                uploadStatus.textContent = 'Caricamento completato con successo!';
                progressBar.style.backgroundColor = '#4caf50'; // Verde per indicare successo
                errorMessagesDiv.style.display = 'none'; // Nascondi errori se il caricamento ha successo
            }
        } else {
            uploadStatus.textContent = 'Errore durante il caricamento!';
            progressBar.style.backgroundColor = '#f44336'; // Rosso per errore
            errorMessagesDiv.style.display = 'none'; // Nascondi errori generali
        }
    });

    // Gestione degli errori di rete
    xhr.addEventListener('error', function () {
        uploadStatus.textContent = 'Errore di rete. Riprova.';
        progressBar.style.backgroundColor = '#f44336';
        errorMessagesDiv.style.display = 'none'; // Nascondi errori generali
    });

    // Configurazione del file di destinazione con il titolo rinominato
    const movieFile = movieInput.files[0];
    if (movieFile) {
        const fileExtension = movieFile.name.split('.').pop(); // Estrai l'estensione del file
        const newFileName = `${title}.${fileExtension}`; // Rinomina il file con il titolo
        formData.append('renamedFileName', newFileName); // Passa il nuovo nome al server
    }

    const posterFile = posterInput.files[0];
    if (posterFile) {
        const posterExtension = posterFile.name.split('.').pop(); // Estrai l'estensione del poster
        const newPosterName = `${title}-poster.${posterExtension}`; // Rinomina il poster
        formData.append('renamedPosterName', newPosterName); // Passa il nuovo nome del poster al server
    }

    // Invio della richiesta AJAX
    xhr.send(formData);
});

// Funzione per visualizzare gli errori
function displayErrors(errors) {
    // Mostra la sezione degli errori
    errorMessagesDiv.style.display = 'block';
    errorMessagesDiv.innerHTML = ''; // Pulisci eventuali messaggi precedenti

    // Aggiungi ogni errore all'elenco
    errors.forEach(function (error) {
        const errorElement = document.createElement('p');
        errorElement.textContent = error;
        errorMessagesDiv.appendChild(errorElement);
    });
}




