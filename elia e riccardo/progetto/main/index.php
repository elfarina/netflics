<?php

//controllo sessione
session_start();
if(!isset($_SESSION['username'])){
    $error_message = "sessione scaduta";
    header("Location: form.html?errors=$error_message");
    exit();
}
if(!isset($_COOKIE['user-data'])) {
    $movies = [];
    $series = [];
    $moviesJson = json_encode($movies);
    $seriesJson = json_encode($series);
    setcookie('username', $_SESSION['username'], time() + (86400 * 30 * 30), "/");  
    setcookie('movies-data-json', $moviesJson, time() + (86400 * 30 * 30), "/");  
    setcookie('series-data-json', $seriesJson, time() + (86400 * 30 * 30), "/");
}

require "backend/user-connect.php";

function get_data($conn)
{
    $content_query = "SELECT c.id, c.title, c.description, c.release_year, c.type, c.poster_url, c.rating, 
                    MAX(csv.series_seasons_count) AS series_seasons_count
                    FROM content_cast_series_view csv 
                    JOIN content c ON csv.content_id = c.id 
                    INNER JOIN content_genres cg ON c.id = cg.content_id 
                    INNER JOIN genres g ON cg.genre_id = g.id 
                    GROUP BY c.id, c.title, c.description, c.release_year, c.type, c.poster_url, c.rating
                    ORDER BY c.id ASC";

    $results = $conn->query($content_query);

    $rows = [];
    if ($results->num_rows > 0) {
        while ($row = $results->fetch_assoc()) {
            $rows[] = $row;
        }
    } else {
        die("Nessun contenuto trovato.");
    }

    // Prendi un elemento casuale e ottieni il cast
    $random_element = $rows[array_rand($rows)];
    $main_element = get_cast($conn, $random_element);

    // Stampa il poster principale
    print_main_poster($main_element);
}

function get_cast($conn, $element)
{
    // Usa una query preparata per evitare SQL Injection
    $sql = "SELECT * FROM content_cast_view WHERE content_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $element['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Inizializza il cast come array
    $element['cast'] = [
        'director' => null, // Inizializza il direttore come null
        'actors' => [] // Array per gli attori
    ];

    if ($result->num_rows > 0) {
        while ($r = $result->fetch_assoc()) {
            if ($r['cast_role'] === 'director') {
                $element['cast']['director'] = $r['cast_name'];
            } else {
                $element['cast']['actors'][] = $r['cast_name'];
            }
        }
    }

    return $element;
}

function print_main_poster($element)
{
    // Controlla se esiste il direttore
    $director = isset($element['cast']['director']) ? $element['cast']['director'] : "N/A";

    // Prendi i primi 2 attori (se disponibili)
    $actors = $element['cast']['actors'];
    $actor_list = count($actors) > 0 ? implode(', ', array_slice($actors, 0, 2)) : "N/A";
    // 
    echo
    '
    <section class="main-poster" style="background: linear-gradient(180deg, rgba(19,19,19,0) 0%, rgba(19,19,19,1) 100%), url(\'images/posters/horizontal/' . $element['title'] . '.jpg\') no-repeat center;">
    <div class="title-container">
        <div class="title">
            ' . htmlspecialchars($element['title']) . '
        </div>
        <div class="info">
            <span class="valutation">
                <div class="valutation-vote">' . htmlspecialchars($element['rating']) . '</div>
            </span>
            <span class="description">
                <div class="description-text">
                ' . htmlspecialchars($element['description']) . '
                </div>
                <div class="description-table">
                    <div class="description-row">
                        <div class="description-row-title">Cast:</div>
                        <div class="description-row-content">
                        ' . htmlspecialchars($director) . ', ' . htmlspecialchars($actor_list) . '
                        </div>
                    </div>
                </div>
            </span>
            <div class="buttons-group">
                <a href="play.php?cid=' . htmlspecialchars($element['id']) . '" class="play-button">
                    <i class="fa fa-play"></i>
                    Riproduci
                </a>
            </div>
        </div>
    </div>
    </section>
    ';
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- my -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/poster.css">

    <!-- font-awesome -->
    <!-- <link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Netteflics • Home</title>
</head>

<body>
    <header>
        <nav class="header-nav">
            <div class="nav-element">
                <div class="logo-container">
                    <img src="images/logo.svg" alt="logo" class="main-logo">
                </div>
            </div>
            <div class="nav-element">
                <div class="nav-btn-group text-uppercase fw-semibold">
                    <a href="index.php" class="nav-btn">Home</a>
                    <a href="library.php?type=movie" class="nav-btn">Film</a>
                    <a href="library.php?type=series" class="nav-btn">Serie</a>
                </div>
            </div>
            <div class="nav-element">
                <div class="nav-utilities">
                    <div class="nav-search-bar">
                        <div class="btn btn-outline-dark text-white" id="bottone-ricerca">
                            <i class="fa fa-search"></i>
                            Cerca
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="search-container">
            <div class="search-bar-container">
                <form action="library.php" class="search-form" method="GET">
                    <input class="search-bar" type="search" name="t" id="search-input" placeholder="Cerca un film..."
                        aria-label="Cerca un film">
                </form>
            </div>
            <div class="search-results" aria-live="polite">
                <!-- ajax result -->
            </div>
        </div>

    </header>
    <div class="main">
            <?php 
            get_data($conn);
            ?>
        <section class="movie-collector">
            <div class="movie-list-container">
                <div class="movie-list-title">Film</div>
                <div class="movie-list-wrapper">
                    <div class="movie-list">
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/cars_4.png" alt="cars_4">
                            <span class="movie-list-item-title">Cars 4</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/deadpool.jpeg" alt="deadpool">
                            <span class="movie-list-item-title">Deadpool</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/fight_club.jpeg"
                                alt="fight_club">
                            <span class="movie-list-item-title">Fight Club</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/interstellar.jpg"
                                alt="interstellar">
                            <span class="movie-list-item-title">Interstellar</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/king_lion.jpg" alt="re_leone">
                            <span class="movie-list-item-title">Il Re Leone</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/oppenheimer.jpg"
                                alt="oppenheimer">
                            <span class="movie-list-item-title">oppenheimer</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/pulp_fiction.jpg"
                                alt="pulp_fiction">
                            <span class="movie-list-item-title">Pulp Fiction</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/tenet.jpg" alt="tenet">
                            <span class="movie-list-item-title">Tenet</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                    </div>
                    <i class="fa fa-solid fa-caret-right arrow" id="wrapper-arrow"></i>
                </div>
            </div>
            <div class="movie-list-container">
                <div class="movie-list-title">Serie</div>
                <div class="movie-list-wrapper">
                    <div class="movie-list">
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/cars_4.png" alt="cars_4">
                            <span class="movie-list-item-title">Cars 4</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/deadpool.jpeg" alt="deadpool">
                            <span class="movie-list-item-title">Deadpool</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/fight_club.jpeg"
                                alt="fight_club">
                            <span class="movie-list-item-title">Fight Club</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/interstellar.jpg"
                                alt="interstellar">
                            <span class="movie-list-item-title">Interstellar</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/king_lion.jpg" alt="re_leone">
                            <span class="movie-list-item-title">Il Re Leone</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/oppenheimer.jpg"
                                alt="oppenheimer">
                            <span class="movie-list-item-title">oppenheimer</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/pulp_fiction.jpg"
                                alt="pulp_fiction">
                            <span class="movie-list-item-title">Pulp Fiction</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/tenet.jpg" alt="tenet">
                            <span class="movie-list-item-title">Tenet</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                    </div>
                    <i class="fa fa-solid fa-caret-right arrow" id="wrapper-arrow"></i>
                </div>
            </div>
            <div class="movie-list-container">
                <div class="movie-list-title">Documentari</div>
                <div class="movie-list-wrapper">
                    <div class="movie-list">
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/cars_4.png" alt="cars_4">
                            <span class="movie-list-item-title">Cars 4</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/deadpool.jpeg" alt="deadpool">
                            <span class="movie-list-item-title">Deadpool</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/fight_club.jpeg"
                                alt="fight_club">
                            <span class="movie-list-item-title">Fight Club</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/interstellar.jpg"
                                alt="interstellar">
                            <span class="movie-list-item-title">Interstellar</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/king_lion.jpg" alt="re_leone">
                            <span class="movie-list-item-title">Il Re Leone</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/oppenheimer.jpg"
                                alt="oppenheimer">
                            <span class="movie-list-item-title">oppenheimer</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/pulp_fiction.jpg"
                                alt="pulp_fiction">
                            <span class="movie-list-item-title">Pulp Fiction</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                        <a href="#" class="movie-list-item">
                            <img class="movie-list-item-img" src="images/movie-list-items/tenet.jpg" alt="tenet">
                            <span class="movie-list-item-title">Tenet</span>
                            <p class="movie-list-item-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Veritatis laboriosam natus, totam sapiente quasi sint explicabo sequi repellat non
                                eaque.</p>
                        </a>
                    </div>
                    <i class="fa fa-solid fa-caret-right arrow" id="wrapper-arrow"></i>
                </div>
            </div>
        </section>
        <footer>
            <div class="footer-container">
                <div class="footer-desc-title">
                    I nostri social:
                </div>
                <div class="footer-desc-icons">
                    <i class="fa fa-brands fa-instagram"></i>
                    <i class="fa fa-brands fa-facebook-f"></i>
                    <i class="fa fa-brands fa-twitter"></i>
                </div>
            </div>
            <div class="footer-container">
                <img class="footer-logo" src="images/logo.svg" alt="">
                <div class="footer-logo-desc">Tutti i diritti sono riservati (ovviamente si scherza è solo a scopo
                    didattico ciaone)<br> se stai ancora leggendo questa cosa forse dovresti guardare gli altri film
                </div>
            </div>
        </footer>
    </div>
    <script src="app.js"></script>
</body>

</html>