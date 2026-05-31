<?php
// Page de paiement : prépare la transaction puis redirige vers la plateforme CYBank.
session_start();
require __DIR__ . "/Traitements/securite.php";

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    header("Location: Connexion.php");
    exit;
}

if (!isset($_SESSION['id_commande_paiement'])) {
    header("Location: Profil.php");
    exit;
}

$idCommande = $_SESSION['id_commande_paiement'];

$commandes = json_decode(file_get_contents('Data/commandes.json'), true);
$commande = null;
foreach ($commandes as $cmd) {
    if ($cmd['id'] === $idCommande) {
        $commande = $cmd;
        break;
    }
}

if ($commande === null) {
    header("Location: Profil.php");
    exit;
}

require('Traitements/getapikey.php');

$vendeur = 'MI-3_A';
$api_key     = getAPIKey($vendeur);
$transaction = preg_replace('/[^a-zA-Z0-9]/', '', $commande['id']);

if ($commande['statut'] === 'en_attente_paiement_supplement' && isset($commande['supplement'])) {
    $montant = number_format($commande['supplement'], 2, '.', '');
    $estSupplement = true;
} else {
    $montant = number_format($commande['total'], 2, '.', '');
    $estSupplement = false;
}

$retour      = 'http://localhost:8000/retour_Paiement.php?id_commande=' . $idCommande;
$control     = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");
?>
<!doctype html>
<html>
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="images/logo.png"/>
    <link rel="apple-touch-icon" sizes="32x32" href="images/logo.png">
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Commandes.css">
    <title>Paiement</title>
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

            <h1>Paiement</h1>

            <section id="resume">
                <h2>Commande <?php echo htmlspecialchars($commande['id']); ?></h2>
                <?php if ($estSupplement) : ?>
                    <p><strong>Supplément à payer (modification) :</strong> <?php echo number_format($commande['supplement'], 2, ',', ''); ?> €</p>
                <?php else : ?>
                    <p><strong>Total à payer :</strong> <?php echo number_format($commande['total'], 2, ',', ''); ?> €</p>
                <?php endif; ?>
            </section>

            <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
                <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
                <input type="hidden" name="montant" value="<?php echo $montant; ?>">
                <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
                <input type="hidden" name="retour" value="<?php echo $retour; ?>">
                <input type="hidden" name="control" value="<?php echo $control; ?>">
                <button type="submit" id="confirmer">Payer par carte bancaire</button>
            </form>

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