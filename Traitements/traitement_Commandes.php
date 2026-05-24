<?php
session_start();
require __DIR__ . '/calcul_panier.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Commandes.php");
    exit;
}

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    header("Location: ../Connexion.php");
    exit;
}

$mode    = $_POST['mode'] ?? 'livraison';
$creneau = $_POST['creneau'] ?? 'maintenant';
$date    = $_POST['date'] ?? '';
$heure   = $_POST['heure'] ?? '';

$plats = json_decode(file_get_contents('../Data/plats.json'), true);
$menus = json_decode(file_get_contents('../Data/menus.json'), true);
$platsParId = [];
foreach ($plats as $p) {
    $platsParId[$p['id']] = $p;
}

$articles = [];
foreach ($_SESSION['panier'] as $id => $qte) {
    if (isset($platsParId[$id])) {
        $articles[] = [
            'id'   => $id,
            'nom'  => $platsParId[$id]['nom'],
            'prix' => $platsParId[$id]['prix'],
            'qte'  => $qte,
        ];
    }
}

$calcul = calculerTotalPanier($_SESSION['panier'], $platsParId, $menus);
$total = $calcul['total'];
$boxesAppliquees = $calcul['boxes'];

$utilisateurs = json_decode(file_get_contents('../Data/users.json'), true);
$user = null;
foreach ($utilisateurs as $u) {
    if ($u['identifiant'] === $_SESSION['identifiant']) {
        $user = $u;
        break;
    }
}

$commande = [
    'id'             => 'CMD-' . strtoupper(uniqid()),
    'client'         => $_SESSION['identifiant'],
    'nom_client'     => $user['nom'] . ' ' . $user['prenom'],
    'articles'       => $articles,
    'boxes'          => $boxesAppliquees,
    'total'          => $total,
    'mode'           => $mode,
    'creneau'        => $creneau === 'plusTard' ? $date . ' ' . $heure : 'Dès que possible',
    'adresse'        => $user['adresse'],
    'statut'         => 'en_attente_paiement',
    'livreur'        => '',
    'date_commande'  => date('Y-m-d H:i:s'),
];

$chemin = '../Data/commandes.json';

if (file_exists($chemin)) {
    $commandes = json_decode(file_get_contents($chemin), true);
} else {
    $commandes = [];
}

$commandes[] = $commande;

file_put_contents(
    $chemin,
    json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

$_SESSION['panier'] = [];
$_SESSION['id_commande_paiement'] = $commande['id'];

header("Location: ../Paiement.php");
exit;