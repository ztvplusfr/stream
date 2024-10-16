<?php
// Inclure l'en-tête
include './includes/header.php';

// Récupérer l'ID TMDB et le titre depuis l'URL
if (isset($_GET['id'])) {
    $tmdb_id = intval($_GET['id']); // Convertir l'ID en entier pour éviter les failles de sécurité
    $title = htmlspecialchars($_GET['title']);
} else {
    // Rediriger ou afficher une erreur si l'ID n'est pas fourni
    header("Location: ./index.php");
    exit;
}

// API TMDB
$apiKey = 'd547a077baa00b34dcb5efb6440a4b04';
$urlMovie = "https://api.themoviedb.org/3/movie/{$tmdb_id}?api_key={$apiKey}&language=fr-FR";
$urlTv = "https://api.themoviedb.org/3/tv/{$tmdb_id}?api_key={$apiKey}&language=fr-FR"; // Pour les séries
$responseMovie = file_get_contents($urlMovie);
$responseTv = file_get_contents($urlTv);
$dataMovie = json_decode($responseMovie, true);
$dataTv = json_decode($responseTv, true);

// Déterminer quel type de programme récupérer (film ou série)
$data = !empty($dataMovie) && isset($dataMovie['id']) ? $dataMovie : ( !empty($dataTv) && isset($dataTv['id']) ? $dataTv : null);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href="./css/video.css"> <!-- Ajoutez votre fichier CSS pour le style -->
    <link href="https://fonts.googleapis.com/css2?family=FFF+Azadliq&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: white;
            font-family: 'FFF Azadliq', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            text-align: center;
        }
        .poster {
            width: 300px; /* Largeur de l'affiche */
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        p {
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .details {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
        }
        .rating {
            margin: 10px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($data) : ?>
            <img src="https://image.tmdb.org/t/p/w500<?php echo $data['poster_path']; ?>" alt="<?php echo $title; ?>" class="poster">
            <h1><?php echo $title; ?></h1>
            <div class="details">
                <p><strong>Synopsis:</strong> <?php echo $data['overview']; ?></p>
                <p><strong>Durée:</strong> <?php echo isset($data['runtime']) ? $data['runtime'] . ' minutes' : 'N/A'; ?></p>
                <p class="rating"><strong>Notation:</strong> <?php echo isset($data['vote_average']) ? $data['vote_average'] . '/10' : 'N/A'; ?></p>
            </div>
        <?php else : ?>
            <p>Aucun détail disponible pour ce programme.</p>
        <?php endif; ?>
    </div>

    <?php include './includes/navbar.php'; ?>
</body>
</html>
