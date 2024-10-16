<?php
// Inclure le header
include 'https://65512aaf-26c2-42e3-bc4a-4161390afdb1-00-2pfry130zc5il.riker.replit.dev/includes/header.php'; 

// Récupérer l'ID TMDB et le titre depuis l'URL
$url_path = $_SERVER['REQUEST_URI']; // Récupère l'URL actuelle
$url_segments = explode('/', $url_path); // Divise l'URL en segments

// Le dernier segment doit être sous la forme {tmdb_id-titre}, par exemple "917496-beetlejuice-beetlejuice"
$last_segment = end($url_segments); // Obtenir la dernière partie de l'URL
list($tmdb_id, $title) = explode('-', $last_segment, 2); // Séparer l'ID TMDB et le titre

// Assurer que l'ID TMDB est bien un entier
$tmdb_id = intval($tmdb_id);

// Sécuriser le titre pour éviter des attaques XSS
$title = htmlspecialchars($title);

// API TMDB pour récupérer les informations du film ou de la série
$apiKey = 'd547a077baa00b34dcb5efb6440a4b04';
$urlMovie = "https://api.themoviedb.org/3/movie/{$tmdb_id}?api_key={$apiKey}&language=fr-FR";
$urlTv = "https://api.themoviedb.org/3/tv/{$tmdb_id}?api_key={$apiKey}&language=fr-FR";

// Essayer de récupérer les informations du film ou de la série
$responseMovie = @file_get_contents($urlMovie);
$responseTv = @file_get_contents($urlTv);
$dataMovie = json_decode($responseMovie, true);
$dataTv = json_decode($responseTv, true);

// Déterminer s'il s'agit d'un film ou d'une série
$data = !empty($dataMovie) && isset($dataMovie['id']) ? $dataMovie : ( !empty($dataTv) && isset($dataTv['id']) ? $dataTv : null);

// Si aucune donnée valide n'est trouvée, afficher une erreur
if (!$data) {
    echo "<p>Impossible de récupérer les informations du programme. Veuillez réessayer plus tard.</p>";
    include './includes/footer.php';
    exit;
}

// Extraire les informations pertinentes
$programTitle = $data['title'] ?? $data['name'];
$overview = $data['overview'];
$runtime = $data['runtime'] ?? ($data['episode_run_time'][0] ?? 'N/A'); // Durée du film ou de l'épisode
$voteAverage = $data['vote_average'];
$poster = 'https://image.tmdb.org/t/p/w500' . $data['poster_path'];
$releaseDate = $data['release_date'] ?? $data['first_air_date'];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css"> <!-- Créez un fichier CSS dédié au style Netflix -->
    <title><?php echo $programTitle; ?></title>
    <style>
        body {
            background-color: #141414;
            color: #fff;
            font-family: 'FFF Azadliq', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .poster {
            width: 300px;
            border-radius: 10px;
            margin-right: 20px;
        }

        .info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info h1 {
            font-size: 2.5rem;
            margin: 0;
            padding: 0;
        }

        .info p {
            font-size: 1.2rem;
            margin: 10px 0;
        }

        .rating {
            color: #46d369;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .duration {
            color: #b3b3b3;
            font-size: 1rem;
        }

        .overview {
            margin-top: 20px;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .content {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .btn-watch {
            background-color: #e50914;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .btn-watch:hover {
            background-color: #f40612;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <img class="poster" src="<?php echo $poster; ?>" alt="<?php echo $programTitle; ?>">
            <div class="info">
                <h1><?php echo $programTitle; ?></h1>
                <p class="rating">Note : <?php echo $voteAverage; ?>/10</p>
                <p class="duration">Durée : <?php echo $runtime; ?> minutes</p>
                <p>Date de sortie : <?php echo $releaseDate; ?></p>
                <p class="overview"><?php echo $overview; ?></p>
                <a href="./play.php?id=<?php echo $tmdb_id; ?>" class="btn-watch">Regarder maintenant</a>
            </div>
        </div>
    </div>

    <?php include 'https://65512aaf-26c2-42e3-bc4a-4161390afdb1-00-2pfry130zc5il.riker.replit.dev/includes/footer.php'; ?>
</body>
</html>
