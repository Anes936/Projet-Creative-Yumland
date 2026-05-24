<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Profil.php");
    exit;
}

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    header("Location: ../Connexion.php");
    exit;
}

$idCommande     = $_POST['id_commande']     ?? '';
$noteLivraison  = (int) ($_POST['note_livraison'] ?? 0);
$noteProduits   = (int) ($_POST['note_produits']  ?? 0);
$commentaire    = trim($_POST['commentaire'] ?? '');

if ($idCommande === '' || $noteLivraison < 1 || $noteLivraison > 5
    || $noteProduits < 1 || $noteProduits > 5) {
    header("Location: ../Profil.php");
    exit;
}

$chemin = '../Data/commandes.json';
$commandes = json_decode(file_get_contents($chemin), true);

foreach ($commandes as &$cmd) {
    if ($cmd['id'] === $idCommande
        && $cmd['client'] === $_SESSION['identifiant']
        && $cmd['statut'] === 'livree') {

        if (!empty($cmd['note'])) {
            break;
        }

        $cmd['note'] = [
            'note_livraison' => $noteLivraison,
            'note_produits'  => $noteProduits,
            'commentaire'    => $commentaire,
            'date'           => date('Y-m-d H:i:s'),
        ];
        break;
    }
}
unset($cmd);

file_put_contents(
    $chemin,
    json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

header("Location: ../Profil.php");
exit;
