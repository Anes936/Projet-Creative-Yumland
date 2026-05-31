<?php
// Restaurateur : fait avancer le statut d'une commande et l'assigne à un livreur.
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../CommandeRestaurateur.php");
    exit;
}

if ($_SESSION['role'] !== 'restaurateur') {
    header("Location: ../Accueil.php");
    exit;
}

$idCommande = $_POST['id_commande'] ?? '';
$action     = $_POST['action'] ?? '';
$livreur    = $_POST['livreur'] ?? '';

if ($action === 'assigner' && $livreur === '') {
    header("Location: ../CommandeRestaurateur.php");
    exit;
}

$chemin = '../Data/commandes.json';
$commandes = json_decode(file_get_contents($chemin), true);

foreach ($commandes as &$cmd) {
    if ($cmd['id'] === $idCommande) {

        if ($action === 'demarrer' && $cmd['statut'] === 'payee') {
            $cmd['statut'] = 'en_preparation';

        } elseif ($action === 'prete' && $cmd['statut'] === 'en_preparation') {
            $cmd['statut'] = 'prete';

        } elseif ($action === 'assigner' && $cmd['statut'] === 'prete') {
            $cmd['statut']  = 'en_livraison';
            $cmd['livreur'] = $livreur;
        }
        break;
    }
}
unset($cmd);

file_put_contents(
    $chemin,
    json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

header("Location: ../CommandeRestaurateur.php");
exit;
