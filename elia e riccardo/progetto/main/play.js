const video = document.getElementById('video');
const playButton = document.getElementById('play_button');
const backward = document.getElementById('backward');
const forward = document.getElementById('forward');
const progress = document.getElementById('progress');
const volume = document.getElementById('volume');
const fullscreen = document.getElementById('fullscreen');
const controls = document.getElementById('controls');
const videoDataContainer = document.querySelector('.video-data-container');
const videoContainer = document.getElementById('video-container');
const progressValue = document.querySelector('.progress-value');

let controlsTimeout;

// Mostra i controlli e i dati video
function showControls() {
    controls.classList.remove('hidden'); // Mostra i controlli
    videoDataContainer.classList.add('visible'); // Aggiungi la classe 'visible' per mostrare i dati video
    videoContainer.classList.add('show-cursor'); // Mostra il cursore
    clearTimeout(controlsTimeout); // Resetta il timer
    controlsTimeout = setTimeout(hideControls, 5000); // Nasconde i controlli dopo 5s
}

// Nascondi i controlli e i dati video
function hideControls() {
    controls.classList.add('hidden');
    videoDataContainer.classList.remove('visible'); // Rimuove la classe 'visible' per nascondere i dati video
    videoContainer.classList.remove('show-cursor'); // Nasconde il cursore
}

// Play/Pausa
playButton.addEventListener('click', () => {
    if (video.paused) {
        video.play();
        playButton.innerHTML = '<i class="fa fa-pause"></i>';
    } else {
        video.pause();
        playButton.innerHTML = '<i class="fa fa-play"></i>';
    }
});
// Avanzamento veloce e riavvolgimento
backward.addEventListener('click', () => {
    video.currentTime = Math.max(0, video.currentTime - 15);
});
forward.addEventListener('click', () => {
    video.currentTime = Math.min(video.duration, video.currentTime + 15);
});

// Aggiorna barra di avanzamento
video.addEventListener('timeupdate', () => {
    progress.value = (video.currentTime / video.duration) * 100;
});

// Seleziona nuova posizione
progress.addEventListener('input', () => {
    video.currentTime = (progress.value / 100) * video.duration;
});

// Controllo volume
volume.addEventListener('input', () => {
    video.volume = volume.value / 100;
});

// Attiva/disattiva fullscreen
fullscreen.addEventListener('click', () => {
    if (!document.fullscreenElement) {
        videoContainer.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
});

// Mostra i controlli e i dati quando il mouse si muove
videoContainer.addEventListener('mousemove', showControls);

// Nasconde i controlli e i dati quando il mouse esce
videoContainer.addEventListener('mouseleave', () => {
    hideControls();
    clearTimeout(controlsTimeout);
});

// Inizializza i controlli visibili
document.addEventListener('DOMContentLoaded', showControls);

// Funzione per formattare il tempo in HH:MM:SS
function formatTime(seconds) {
    const hours = Math.floor(seconds / 3600); // Calcola le ore
    const minutes = Math.floor((seconds % 3600) / 60); // Calcola i minuti
    const secs = Math.floor(seconds % 60); // Calcola i secondi

    // Ritorna il tempo nel formato HH:MM:SS
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}
// Aggiorna la progress bar e il minutaggio
video.addEventListener('timeupdate', () => {
    // Aggiorna il valore della progress bar
    progress.value = (video.currentTime / video.duration) * 100;

    // Aggiorna il minutaggio nel progress-value
    progressValue.textContent = `${formatTime(video.currentTime)} / ${formatTime(video.duration)}`;
});

// Inizializzazione delle variabili globali

const contentList = document.querySelector(".content-list");
const subtitle = document.querySelector('.subtitle');
// const video = document.querySelector("video"); // Assumendo che video sia un elemento HTML <video>

// Funzione per caricare episodi di una stagione
function loadEpisodes(season) {
    fetch(`backend/get_episodes.php?cid=${cid}&season=${season}`)
        .then(response => {
            if (!response.ok) throw new Error('Errore nella risposta del server');
            return response.json();
        })
        .then(episodes => {
            contentList.innerHTML = ''; // Pulizia lista episodi
            episodes.forEach(episode => {
                const episodeElement = createEpisodeElement(episode);
                contentList.appendChild(episodeElement);
            });
        })
        .catch(error => {
            console.error(error);
            showErrorModal("Errore nel caricamento degli episodi. Riprova più tardi.");
        });
}

// // Gestione episodi per serie
// if (type === 'series') {
//     let currentSeason = <? php echo $dataArray['ssnum']; ?>;
//     let maxSeason = <? php echo $dataArray['sscount']; ?>;

//     const leftWrapperArrow = document.getElementById('left-wrapper-arrow');
//     const rightWrapperArrow = document.getElementById('right-wrapper-arrow');

//     function updateArrows() {
//         leftWrapperArrow.disabled = currentSeason <= 1;
//         rightWrapperArrow.disabled = currentSeason >= maxSeason;
//     }

//     leftWrapperArrow.addEventListener('click', () => {
//         if (currentSeason > 1) {
//             currentSeason--;
//             subtitle.textContent = `Stagione ${currentSeason}`;
//             loadEpisodes(currentSeason);
//             updateArrows();
//         }
//     });

//     rightWrapperArrow.addEventListener('click', () => {
//         if (currentSeason < maxSeason) {
//             currentSeason++;
//             subtitle.textContent = `Stagione ${currentSeason}`;
//             loadEpisodes(currentSeason);
//             updateArrows();
//         }
//     });

//     loadEpisodes(currentSeason);
//     updateArrows();
// } else {
//     loadCorrelated(cid); // Se non è una serie, carica contenuti correlati
// }

// Funzione per caricare contenuti correlati
function loadCorrelated(cid) {
    fetch(`backend/get_correlated.php?cid=${cid}`)
        .then(response => {
            if (!response.ok) throw new Error('Errore nella risposta del server');
            return response.json();
        })
        .then(correlated => {
            contentList.innerHTML = ''; // Pulizia lista contenuti correlati
            correlated.forEach(content => {
                const contentElement = createCorrelatedElement(content);
                contentList.appendChild(contentElement);
            });
        })
        .catch(error => showErrorModal("Errore nel caricamento dei contenuti correlati. Riprova più tardi."));
}
// Funzioni helper per creare elementi


function createCorrelatedElement(content) {
    const contentElement = document.createElement('a');
    contentElement.href = `play.php?cid=${content.cid}`;
    contentElement.classList.add('movie');

    const imgElement = document.createElement('img');
    imgElement.src = `${content.poster_url}/horizontal/${content.title}.jpg`;

    const titleElement = document.createElement('span');
    titleElement.classList.add('img-title');
    titleElement.textContent = `${content.title}: [${content.rating}/5]`;

    contentElement.appendChild(imgElement);
    contentElement.appendChild(titleElement);

    return contentElement;
}

// Salvataggio e caricamento cookie
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;
}

function isValidJson(str) {
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
}

function getCookie(name) {
    const cookies = document.cookie.split("; ");
    for (let cookie of cookies) {
        const [key, value] = cookie.split("=");
        if (key === name) {
            const decodedValue = decodeURIComponent(value);
            return isValidJson(decodedValue) ? decodedValue : null;
        }
    }
    return null;
}

// Funzione per salvare il watchtime nei cookie
function saveWatchTime(cid, ssnum, epid, watchtime) {
    let watchedVideos = getCookie("watchedVideos");
    if (watchedVideos) {
        // Decodifica e fai il parsing del valore del cookie
        watchedVideos = JSON.parse(decodeURIComponent(watchedVideos));
    } else {
        watchedVideos = [];
    }

    let videoIndex = watchedVideos.findIndex(video => video.cid === cid && video.ssnum === ssnum && video.epid === epid);
    if (videoIndex !== -1) {
        watchedVideos[videoIndex].watchtime = watchtime;
    } else {
        watchedVideos.push({ cid, ssnum, epid, watchtime });
    }

    // Salva il valore del cookie come JSON, e codificalo per evitare problemi di encoding
    setCookie("watchedVideos", encodeURIComponent(JSON.stringify(watchedVideos)), 30);

    // Salva anche l'ultimo watchtime nei cookie
    setCookie(`lastWatched_${cid}`, encodeURIComponent(`${ssnum}_${epid}_${watchtime}`), 30);
}

// Funzione per caricare l'ultimo watchtime
function loadLastWatched(cid) {
    const key = `lastWatched_${cid}`;
    const value = getCookie(key);
    if (value) {
        const [ssnum, epid, watchtime] = value.split("_");
        return { ssnum: parseInt(ssnum), epid: parseInt(epid) || null, watchtime: parseFloat(watchtime) };
    }
    return null;
}
video.addEventListener("ended", () => {
    const nextButton = document.getElementById("next-episode-button");
    if (nextButton) nextButton.click(); // Passa al prossimo episodio
});

// Funzione per mostrare modale di errore
function showErrorModal(message) {
    const modalHTML = `
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Errore</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ${message}
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
        </div>
    </div>
</div>
</div>`;
    document.body.insertAdjacentHTML("beforeend", modalHTML);
    const modal = new bootstrap.Modal(document.getElementById("errorModal"));
    modal.show();
}
