<?php
// classes/Vue.php

abstract class Vue {
    protected $titre = "SoKnow - Entraide Intergénérationnelle";

    // La méthode principale qui orchestre l'affichage
    public function afficher($donnees = []) {
        // 1. Démarre la mise en tampon (ob_start) pour capturer le contenu spécifique
        ob_start();
        $this->afficherContenu($donnees);
        $contenu = ob_get_clean();

        // 2. Inclut le squelette HTML global
        ?>
        <!DOCTYPE html>
        <html lang="<?php echo $_SESSION['lang'] ?? 'fr'; ?>">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $this->titre; ?></title>
            
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
            
            <link rel="stylesheet" href="assets/css/style.css">
            <link rel="stylesheet" href="assets/css/header.css">
            <link rel="stylesheet" href="assets/css/dashboard.css">
            <link rel="stylesheet" href="assets/css/messages.css">
            <link rel="stylesheet" href="assets/css/calendar.css">
            
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        </head>
        <body class="sk-body">

            <?php include 'includes/header.php'; ?>

            <?php $currentPage = $_GET['page'] ?? 'home'; ?>

            <main class="container">
                <?php echo $contenu; ?>
            </main>

            <?php if ($currentPage !== 'messages'): ?>
                <?php include 'includes/footer.php'; ?>
            <?php endif; ?>

            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script src="js/main.js"></script>
        </body>
        </html>
        <?php
    }

  
    abstract protected function afficherContenu($donnees);
}