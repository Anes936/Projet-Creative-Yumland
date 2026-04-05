<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Livraison.php");
    exit;
}

if ($_SESSION['role'] !== 'livreur') {
    header("Location: ../Accueil.php");
    exit;
}

$idCommande = $_POST['id_commande'] ?? '';
$action     = $_POST['action'] ?? '';

$chemin = '../Data/commandes.json';
$commandes = json_decode(file_get_contents($chemin), true);

foreach ($commandes as &$cmd) {
    if ($cmd['id'] === $idCommande) {
        if ($action === 'livree') {
            $cmd['statut'] = 'livree';
        } elseif ($action === 'abandonnee') {
            $cmd['statut'] = 'abandonnee';
        }
        break;
    }
}

file_put_contents(
    $chemin,
    json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

header("Location: ../Livraison.php");
exit;
?>