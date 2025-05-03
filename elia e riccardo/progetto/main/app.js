document.addEventListener("DOMContentLoaded", () => {
    const arrows = document.querySelectorAll(".arrow"); //freccie del wrapper dei film nell'index
    const movieLists = document.querySelectorAll(".movie-list"); //container dei film nel wrapper (array di film)
    //attribuisco ad ogni freccia la logica per scorrere a destra nel wrapper: calcolo la grandezza della pagina e scorro della grandezza adatta (vedi variabili e commenti)
    arrows.forEach((arrow, i) => {
        const movieList = movieLists[i]; 
        const items = movieList.querySelectorAll(".movie-list-item"); // Tutti gli elementi della lista
        const remInPixels = parseFloat(getComputedStyle(document.documentElement).fontSize); // 1 rem in pixel
        const padding = 1.5 * remInPixels * 2; // Padding totale (sinistra + destra)
        const itemWidth = items[0].getBoundingClientRect().width + padding; // Larghezza di un elemento con padding
        const containerWidth = movieList.parentElement.getBoundingClientRect().width; // Larghezza del contenitore visibile
        const visibleItems = containerWidth / itemWidth; // Elementi visibili, inclusi quelli frazionari
        const maxScrolls = Math.ceil(items.length - visibleItems) - 1; // Riduci di 1 il massimo numero di cicli

        let clickCounter = 0;

        arrow.addEventListener("click", () => {
            if (clickCounter < maxScrolls) {
                clickCounter++;
                const translateX = -clickCounter * itemWidth; // Traslazione totale
                movieList.style.transform = `translateX(${translateX}px)`;
            } else {
                movieList.style.transform = "translateX(0)"; // Torna all'inizio
                clickCounter = 0;
            }
        });

        // Debugging
        console.log(`Container width: ${containerWidth}px`);
        console.log(`Item width (with padding): ${itemWidth}px`);
        console.log(`Visible items (fractions included): ${visibleItems}`);
        console.log(`Max scrolls (reduced by 1): ${maxScrolls}`);
    });
    


    // open/close search-layer
    const searchButton = document.getElementById("bottone-ricerca");
    const searchContainer = document.querySelector(".search-container");
    searchButton.addEventListener("click", () => {
        if (searchContainer.classList.contains("open")) {
            // Chiudi il contenitore
            searchContainer.classList.remove("open");
            setTimeout(() => {
                searchContainer.style.display = "none"; // Nascondi dopo l'animazione
            }, 500); // Durata corrisponde alla transizione CSS
        } else {
            searchContainer.style.display = "flex"; // Mostra visivamente
            setTimeout(() => {
                searchContainer.classList.add("open"); // Attiva l'animazione
            }, 10); // Breve ritardo per garantire il calcolo della transizione
        }
    });


    document.getElementById("search-input").addEventListener("input", function () {
        const query = this.value.trim();
        const resultsContainer = document.querySelector(".search-results");
    
        // Se il campo di input è vuoto, svuota i risultati e termina.
        if (query === "") {
            resultsContainer.innerHTML = ""; //svuota se è vuoto
            return;
        }
    
        // Mostra il messaggio "Caricamento..."
        resultsContainer.innerHTML = "<i class='fa fa-spinner'></i>";
    
        // Creazione della richiesta AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `backend/ajax-film.php?q=${encodeURIComponent(query)}`, true);
    
        xhr.onload = function () {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
    
                // Cancella risultati precedenti
                resultsContainer.innerHTML = "";
    
                // Mostra risultati
                if (data.length > 0) {
                    data.forEach(film => {
                        const div = document.createElement("div");
                        div.className = "search-output-row";
    
                        // Genera le stelle per il rating
                        let starsHtml = '';
                        let fullStars = Math.floor(film.rating); // Stelle piene
                        let halfStars = (film.rating % 1 !== 0) ? 1 : 0; // Mezza stella (se il rating non è un numero intero)
                        let emptyStars = 5 - fullStars - halfStars; // Stelle vuote
    
                        // Aggiungi le stelle piene
                        for (let i = 0; i < fullStars; i++) {
                            starsHtml += `<i class="fa fa-star" aria-hidden="true"></i> `;
                        }
    
                        // Aggiungi la mezza stella, se presente
                        if (halfStars > 0) {
                            starsHtml += `<i class="fa fa-star-half-o" aria-hidden="true"></i> `;
                        }
    
                        // Aggiungi le stelle vuote
                        for (let i = 0; i < emptyStars; i++) {
                            starsHtml += `<i class="fa fa-star-o" aria-hidden="true"></i> `; // Stelline vuote
                        }
                        div.innerHTML = `<a href="play.php?cid=${film.id}" class="movie-button">
                        <span class="movie-image">
                            <img class="movie-poster" src="${film.poster_url}horizontal/${film.title}.jpg">
                            <span class="movie-title">${film.title}[${film.release_year}]</span>
                            </span> 
                        <span class="movie-rating">${starsHtml}</span>
                        </a>`;
                        // Aggiungi il risultato alla lista
                        resultsContainer.appendChild(div);
                    });
                } else {
                    resultsContainer.textContent = "Nessun risultato";
                }
            } else {
                resultsContainer.textContent = "Errore nel recupero dei dati";
            }
        };
    
        xhr.onerror = function () {
            resultsContainer.textContent = "Errore di rete. Controlla la connessione.";
        };
    
        xhr.send();
    });
    
    

});

