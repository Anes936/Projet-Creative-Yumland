<?php
// Retour de la plateforme CYBank : on met à jour le statut de la commande selon le paiement.
session_start();

require('Traitements/getapikey.php');

$transaction = $_GET['transaction'] ?? '';
$montant     = $_GET['montant'] ?? '';
$vendeur     = $_GET['vendeur'] ?? '';
$statut      = $_GET['status'] ?? '';
$control     = $_GET['control'] ?? '';
$idCommande  = $_GET['id_commande'] ?? '';

$api_key = getAPIKey($vendeur);
$controlAttendu = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut . "#");

$paiementValide = ($control === $controlAttendu && $statut === 'accepted');

if ($idCommande !== '') {
    $chemin = 'Data/commandes.json';
    $commandes = json_decode(file_get_contents($chemin), true);

    foreach ($commandes as &$cmd) {
        if ($cmd['id'] === $idCommande) {
            if ($paiementValide) {
                $cmd['statut'] = 'payee';
                $cmd['paiement'] = 'accepte';
                unset($cmd['supplement']);
            } else {
                $cmd['statut'] = 'paiement_refuse';
                $cmd['paiement'] = 'refuse';
            }
            break;
        }
    }

    file_put_contents(
        $chemin,
        json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

unset($_SESSION['id_commande_paiement']);
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Commandes.css">
    <title>Retour paiement</title>
    <script src="JS/theme.js" defer></script>
</head>
<body>

    <header>

        <div id="header-left">
            <a href="Panier.php" id="panier">
                <img src="images/Panier.jpeg" alt="Panier">
            </a>
        </div>

        <div id="header-center">
            <a href="Accueil.php">
                <img src="images/Glogo.png" alt="Logo">
            </a>
        </div>

        <button type="button" id="btn-theme" class="btn-theme">Mode sombre</button>
<div id="header-right">
            <a href="Profil.php">
                <img src="images/IconeProfil.png" alt="Mon profil">
            </a>
        </div>

    </header>

    <main>
        <div id="corps">

            <?php if ($paiementValide) : ?>
                <h1>Paiement accepté</h1>
                <p>Votre commande a bien été enregistrée et est en cours de préparation.</p>
            <?php else : ?>
                <h1>Paiement refusé</h1>
                <p>Le paiement n'a pas abouti. Veuillez réessayer.</p>
            <?php endif; ?>

            <a href="Profil.php"><button id="confirmer">Retour à mon profil</button></a>

        </div>
    </main>

   <footer>
        <div id="footer-left">
            <img src="images/logo.png" alt="logo">
        </div>
        <div id="footer-right">
            <a href="Avis.php">Donnez nous votre avis!</a>
        </div>
    </footer>

</body>
</html>