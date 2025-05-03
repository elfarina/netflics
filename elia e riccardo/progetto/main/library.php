<?php 
session_start();
if(!isset($_SESSION['username'])){
    $error_message = "sessione scaduta";
    header("Location: form.html?errors=$error_message");
    exit();
}

function fetchResults($conn, string $column, string $value, bool $boolean) {
    $stmt = $conn->prepare('SELECT c.id, c.title, c.description, c.release_year, c.type,
                            c.poster_url, c.rating, MAX(csv.series_seasons_count) AS series_seasons_count
                            FROM content_cast_series_view csv
                            JOIN content c ON csv.content_id = c.id
                            JOIN cast ca ON csv.cast_id = ca.id
                            INNER JOIN content_genres cg ON c.id = cg.content_id
                            INNER JOIN genres g ON cg.genre_id = g.id
                            WHERE ' . $column . ' LIKE CONCAT(?, "%")
                            GROUP BY c.id, c.title, c.description, c.release_year, c.type, c.poster_url, c.rating
                            ORDER BY c.title;');
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $ris = $stmt->get_result();
    $boolean = $ris->num_rows > 0;
    return $ris;
}

function renderResults(mixed $resultSet, $filterName, $filterValue, bool $boolean) {
    if ($boolean) {
        echo '<div class="library-row">
                <div class="title">
                    Risultati per ' . htmlspecialchars($filterName) . ': 
                    "<span>' . htmlspecialchars($filterValue) . '</span>"
                </div>
              <div class="library-desc">';
        while ($row = $resultSet->fetch_assoc()) {
            echo '<div class="content-list">
                    <a href="play.php?cid=' . htmlspecialchars($row["id"]) . '" class="content">
                        <img class="content-list-item-img" src="' . htmlspecialchars($row["poster_url"]) . '/vertical/' . htmlspecialchars($row["title"]) . '.jpg">
                        <span class="content-list-item-title">' . htmlspecialchars($row["title"]) . '</span>
                        <p class="content-list-item-desc">';
            if ($row["series_seasons_count"] != null) {
                echo '<strong>' . htmlspecialchars($row["series_seasons_count"]) . ' stagioni</strong><br>';
            }
            echo '<strong>[' . htmlspecialchars($row["release_year"]) . '] - ' . htmlspecialchars($row["rating"]) . '/5</strong><br>'
                 . htmlspecialchars($row["description"]) . '</p>
                    </a>
                  </div>';
        }
        echo '</div></div>';
    }
}


// if (isset($_GET['t']) || isset($_GET['p']) || isset($_GET['g']) || isset($_GET['type'])) { 
    // $title=preg_replace("/[^a-zA-Z0-9\s]/", "", filter_input(INPUT_GET, 't' , FILTER_SANITIZE_STRING)); 
    // $person=preg_replace("/[^a-zA-Z0-9\s]/", "" ,filter_input(INPUT_GET, 'p' , FILTER_SANITIZE_STRING)); 
    // $genre=preg_replace("/[^a-zA-Z0-9\s]/", "" ,filter_input(INPUT_GET, 'g' , FILTER_SANITIZE_STRING)); 
    $title=isset($_GET['t']) ? preg_replace("/[^a-zA-Z0-9\s]/", "" , filter_input(INPUT_GET, 't' , FILTER_SANITIZE_STRING)) : '' ;
    $peaple=isset($_GET['p']) ? preg_replace("/[^a-zA-Z0-9\s]/", "" , filter_input(INPUT_GET, 'p' ,FILTER_SANITIZE_STRING)) : '' ;
    $genre=isset($_GET['g']) ? preg_replace("/[^a-zA-Z0-9\s]/", "" ,filter_input(INPUT_GET, 'g' , FILTER_SANITIZE_STRING)) : '' ; 
    $type=isset($_GET['type']) ? preg_replace("/[^a-zA-Z0-9\s]/", "" ,filter_input(INPUT_GET, 'type' , FILTER_SANITIZE_STRING)) : '' ; 
    
    $isTitleOk = false;
    $isGenreOk = false;
    $isPeapleOk = false;
    $isTypeOk = false;
    try {
        require "backend/user-connect.php";
    } catch (Exception $e) {
        error_log($e->getMessage());
        die("Errore nella connessione al database.");
    }
    /*
    $stmt=$conn->prepare('SELECT c.id, c.title, c.description, c.release_year, c.type,
    c.poster_url, c.rating, MAX(csv.series_seasons_count)
    AS series_seasons_count
    FROM content_cast_series_view csv
    JOIN content c ON csv.content_id = c.id
    JOIN cast ca ON csv.cast_id = ca.id
    INNER JOIN content_genres cg ON c.id = cg.content_id
    INNER JOIN genres g ON cg.genre_id = g.id
    WHERE (c.title LIKE CONCAT("%", ?, "%") OR ca.name LIKE CONCAT("%", ?, "%") OR g.name LIKE CONCAT("%", ?, "%") )
    GROUP BY c.id, c.title, c.description, c.release_year, c.type, c.poster_url, c.rating
    ORDER BY c.title;');
    */
    $titleResult = $peapleResult = $genreResult = $typeResult = null;

    if(isset($_GET['t'])){
        $titleResult = fetchResults($conn, 'c.title',  $title, $isTitleOk);
        $isTitleOk = $titleResult->num_rows > 0;
    }

    if(isset($_GET['p'])){
        $peapleResult = fetchResults($conn, 'ca.name',  $peaple, $isPeapleOk);
        $isPeapleOk = $peapleResult->num_rows > 0;
        
    }
    if(isset($_GET['g'])){
        $genreResult = fetchResults($conn, 'g.name',  $genre, $isGenreOk);
        $isGenreOk = $genreResult->num_rows > 0;
    }
    if(isset($_GET['type'])){
        $typeResult = fetchResults($conn, 'c.type',  $type, $isTypeOk);
        $isTypeOk = $typeResult->num_rows > 0;
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- my -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/library.css">
        <!-- font-awesome -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
        <!-- google fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <title>Netteflix • Explore</title>
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
                    <form action="library.php" class="search-form" method="get">
                        <input class="search-bar" type="search" name="t" id="search-input"
                            placeholder="Cerca un film..." aria-label="Cerca un film">
                    </form>
                </div>
                <div class="search-results" aria-live="polite">
                    <!-- ajax result -->
                </div>
            </div>
        </header>
        <section class="library-container">
                    <?php 
                    renderResults($titleResult, 'titolo', $title, $isTitleOk);
                    renderResults($genreResult, 'genere', $genre, $isGenreOk);
                    renderResults($peapleResult, 'cast', $peaple, $isPeapleOk);
                    renderResults($typeResult, 'tipo', $type, $isTypeOk);
                    
                    ?>
                
            </div>
        </section>
        <script src="app.js  "></script>
    </body>

    </html>