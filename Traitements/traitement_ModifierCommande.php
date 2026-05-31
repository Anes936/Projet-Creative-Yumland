<?php
// Modification d'une commande payée : on recalcule le total et on gère l'éventuel supplément.
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    echo json_encode(['ok' => false, 'message' => 'Non connecte.']);
    exit;
}

$donnees = json_decode(file_get_contents('php://input'), true);
$idCommande = $donnees['id_commande'] ?? '';
$nouveauxArticles = $donnees['articles'] ?? [];

if ($idCommande === '' || empty($nouveauxArticles)) {
    echo json_encode(['ok' => false, 'message' => 'Donnees invalides.']);
    exit;
}

$plats = json_decode(file_get_contents('../Data/plats.json'), true);
$platsParId = [];
foreach ($plats as $p) {
    $platsParId[$p['id']] = $p;
}

$chemin = '../Data/commandes.json';
$commandes = json_decode(file_get_contents($chemin), true);

$indexCommande = -1;
foreach ($commandes as $i => $cmd) {
    if ($cmd['id'] === $idCommande
        && $cmd['client'] === $_SESSION['identifiant']
        && $cmd['statut'] === 'payee') {
        $indexCommande = $i;
        break;
    }
}

if ($indexCommande === -1) {
    echo json_encode(['ok' => false, 'message' => 'Commande introuvable ou deja en preparation.']);
    exit;
}

$ancienTotal = (float) $commandes[$indexCommande]['total'];
$articlesComplets = [];
$nouveauTotal = 0;

foreach ($nouveauxArticles as $art) {
    $id  = $art['id'] ?? '';
    $qte = (int) ($art['qte'] ?? 0);
    if ($id === '' || $qte <= 0 || !isset($platsParId[$id])) {
        continue;
    }
    $plat = $platsParId[$id];
    $articlesComplets[] = [
        'id'   => $plat['id'],
        'nom'  => $plat['nom'],
        'prix' => $plat['prix'],
        'qte'  => $qte,
    ];
    $nouveauTotal += $plat['prix'] * $qte;
}

$commandes[$indexCommande]['articles'] = $articlesComplets;
$commandes[$indexCommande]['total']    = $nouveauTotal;

// Si la commande coûte plus cher qu'avant, le client doit payer la différence.
$difference = $nouveauTotal - $ancienTotal;
$paiementSupp = false;

if ($difference > 0.001) {
    $paiementSupp = true;
    $commandes[$indexCommande]['statut'] = 'en_attente_paiement_supplement';
    $commandes[$indexCommande]['supplement'] = round($difference, 2);
    $_SESSION['id_commande_paiement'] = $idCommande;
}

file_put_contents(
    $chemin,
    json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

echo json_encode([
    'ok' => true,
    'paiement_supplementaire' => $paiementSupp,
    'difference' => round($difference, 2)
]);
