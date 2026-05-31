<?php
// Déconnexion : on détruit la session et on revient à l'accueil.
session_start();
session_destroy();
header("Location: ../Accueil.php");
exit;
?>