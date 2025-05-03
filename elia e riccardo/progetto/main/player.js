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
