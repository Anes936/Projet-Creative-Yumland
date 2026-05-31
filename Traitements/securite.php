<?php
// Inclus en tête des pages protégées : déconnecte aussitôt un compte qui vient d'être bloqué.
if (isset($_SESSION['connecte']) && $_SESSION['connecte']) {
    $cheminUsers = __DIR__ . '/../Data/users.json';
    if (file_exists($cheminUsers)) {
        $utilisateurs = json_decode(file_get_contents($cheminUsers), true);
        foreach ($utilisateurs as $u) {
            if ($u['identifiant'] === $_SESSION['identifiant']) {
                if (!empty($u['bloque'])) {
                    session_destroy();
                    header("Location: Accueil.php");
                    exit;
                }
                break;
            }
        }
    }
}
