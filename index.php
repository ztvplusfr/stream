<?php include './includes/header.php'; ?>
<?php
$pageTitle = "Accueil"; // Titre de la page
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href="./css/slider.css"> <!-- Ajoutez votre fichier CSS pour le slider ici -->
    <link href="https://fonts.googleapis.com/css2?family=FFF+Azadliq&display=swap" rel="stylesheet"> <!-- Lien pour la police -->
    <style>
        body {
            background-color: black;
            color: white;
            font-family: 'FFF Azadliq', sans-serif; /* Utiliser la police FFF Azadliq */
        }
        .latest-releases, .current-series {
            display: flex; /* Utiliser flex pour aligner horizontalement */
            overflow-x: auto; /* Permettre le défilement horizontal si nécessaire */
            padding: 10px 0; /* Espacement autour de la liste */
            gap: 10px; /* Espacement entre les posters */
        }
        .latest-releases img, .current-series img {
            width: 100px; /* Rétrécir les images */
            height: auto; /* Assurer la proportionnalité */
            border-radius: 8px; /* Arrondir les coins des images */
            transition: transform 0.3s; /* Animation sur hover */
            cursor: pointer; /* Changer le curseur pour indiquer que l'image est cliquable */
        }
        .latest-releases img:hover, .current-series img:hover {
            transform: scale(1.05); /* Agrandir légèrement l'image au survol */
        }
        h2 {
            font-family: 'FFF Azadliq', sans-serif; /* Utiliser la police FFF Azadliq */
            font-weight: bold; 
            text-transform: uppercase; 
            margin-bottom: 10px; /* Espace en bas du titre */
        }
    </style>
</head>
<body>
    <h1>Bienvenue sur ADN Streaming</h1>
    
    <!-- Slider pour afficher les animes -->
    <div class="anime-slider">
        <div class="slider-container">
            <?php
            $apiKey = 'd547a077baa00b34dcb5efb6440a4b04';
            $url = "https://api.themoviedb.org/3/discover/movie?api_key={$apiKey}&with_genres=16&language=fr-FR"; // Genre anime (16) en français
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            // Vérifiez si des résultats sont renvoyés
            if (!empty($data['results'])) {
                foreach ($data['results'] as $anime) {
                    $animeTitle = htmlspecialchars($anime['title']);
                    $animeBackdrop = 'https://image.tmdb.org/t/p/w1280' . $anime['backdrop_path']; // Utiliser backdrop_path pour un format paysage
                    // Créer un titre sans espaces pour l'URL
                    $formattedTitle = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $animeTitle)));
                    echo "<div class='slide'>
                            <a href='./video/{$anime['id']}-{$formattedTitle}'><img src='{$animeBackdrop}' alt='{$animeTitle}'></a>
                            <h2>{$animeTitle}</h2>
                          </div>";
                }
            } else {
                echo "<p>Aucun anime disponible pour le moment.</p>";
            }
            ?>
        </div>
        <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
        <button class="next" onclick="changeSlide(1)">&#10095;</button>
    </div>

    <!-- Titres pour les sections -->
    <div class="titles">
        <h2>Dernières sorties</h2>
        <div class="latest-releases">
            <?php
            // Obtenir les dernières sorties (films) en français/anglais
            $urlLatest = "https://api.themoviedb.org/3/movie/now_playing?api_key={$apiKey}&language=fr-FR&page=1"; 
            $responseLatest = file_get_contents($urlLatest);
            $dataLatest = json_decode($responseLatest, true);

            // Vérifiez si des résultats sont renvoyés
            if (!empty($dataLatest['results'])) {
                $latestCount = 0; // Compteur pour s'assurer qu'on affiche seulement 5
                foreach ($dataLatest['results'] as $latest) {
                    if ($latestCount >= 5) break; // Limiter à 5 éléments
                    $latestTitle = htmlspecialchars($latest['title']);
                    $latestPoster = 'https://image.tmdb.org/t/p/w500' . $latest['poster_path']; // Utiliser poster_path pour le format vertical
                    // Créer un titre sans espaces pour l'URL
                    $formattedTitle = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $latestTitle)));
                    echo "<a href='./video/{$latest['id']}-{$formattedTitle}'><img src='{$latestPoster}' alt='{$latestTitle}' title='{$latestTitle}'></a>"; // Lien vers le film
                    $latestCount++; // Incrémenter le compteur
                }
            } else {
                echo "<p>Aucune dernière sortie disponible pour le moment.</p>";
            }
            ?>
        </div>

        <h2>Simulcast</h2>
        <div class="current-series">
            <?php
            // Obtenir les séries actuellement diffusées en français/anglais
            $urlCurrentSeries = "https://api.themoviedb.org/3/tv/on_the_air?api_key={$apiKey}&language=fr-FR&page=1"; 
            $responseCurrentSeries = file_get_contents($urlCurrentSeries);
            $dataCurrentSeries = json_decode($responseCurrentSeries, true);

            // Vérifiez si des résultats sont renvoyés
            if (!empty($dataCurrentSeries['results'])) {
                $seriesCount = 0; // Compteur pour s'assurer qu'on affiche seulement 5
                foreach ($dataCurrentSeries['results'] as $series) {
                    if ($seriesCount >= 5) break; // Limiter à 5 éléments
                    $seriesTitle = htmlspecialchars($series['name']);
                    $seriesPoster = 'https://image.tmdb.org/t/p/w500' . $series['poster_path']; // Utiliser poster_path pour le format vertical
                    // Créer un titre sans espaces pour l'URL
                    $formattedTitle = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $seriesTitle)));
                    echo "<a href='./video/{$series['id']}-{$formattedTitle}'><img src='{$seriesPoster}' alt='{$seriesTitle}' title='{$seriesTitle}'></a>"; // Lien vers la série
                    $seriesCount++; // Incrémenter le compteur
                }
            } else {
                echo "<p>Aucune série actuellement diffusée disponible pour le moment.</p>";
            }
            ?>
        </div>
    </div>

    <?php include './includes/navbar.php'; ?>

    <script>
        function changeSlide(direction) {
            // Logique pour changer le slide
            const slides = document.querySelectorAll('.slide');
            let currentSlide = 0;

            slides.forEach((slide, index) => {
                if (slide.style.display !== 'none') {
                    currentSlide = index;
                }
                slide.style.display = 'none'; // Cacher tous les slides
            });

            currentSlide += direction;
            if (currentSlide >= slides.length) currentSlide = 0;
            if (currentSlide < 0) currentSlide = slides.length - 1;
            slides[currentSlide].style.display = 'block'; // Afficher le slide actuel
        }
    </script>
</body>
</html>
