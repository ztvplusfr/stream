<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADN Streaming</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Barre de navigation -->
        <?php include 'navbar.php'; ?>

        <!-- Titre de la page -->
        <header class="header">
            <h1><?php echo isset($pageTitle) ? $pageTitle : 'ADN Streaming'; ?></h1>
        </header>
    </div>
</body>
</html>
