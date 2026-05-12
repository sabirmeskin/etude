<?php
// Standalone layout for auth pages
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mini ERP Scolaire - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
    </style>
</head>
<body>
    <?php
    if (isset($viewFile) && file_exists($viewFile)) {
        include $viewFile;
    } else {
        echo '<h1 class="text-2xl font-bold">Page</h1>';
    }
    ?>
</body>
</html>
