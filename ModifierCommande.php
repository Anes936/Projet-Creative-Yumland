<?php
session_start();
require __DIR__ . "/Traitements/securite.php";

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    header("Location: Connexion.php");
    exit;
}

$idCommande = $_GET['id'] ?? '';
$commandes = json_decode(file_get_contents('Data/commandes.json'), true);

$commande = null;
foreach ($commandes as $cmd) {
    if ($cmd['id'] === $idCommande && $cmd['client'] === $_SESSION['identifiant']) {
        $commande = $cmd;
        break;
    }
}

if ($commande === null) {
    header("Location: Profil.php");
    exit;
}

if ($commande['statut'] !== 'payee') {
    header("Location: Profil.php");
    exit;
}

$plats = json_decode(file_get_contents('Data/plats.json'), true);
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Panier.css">
    <title>Modifier ma commande</title>
    <script src="JS/theme.js" defer></script>
    <script src="JS/modifierCommande.js" defer></script>
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

            <h1>Modifier ma commande</h1>
            <p style="text-align:center; color:#666;">Commande <?php echo htmlspecialchars($commande['id']); ?></p>
            <p style="text-align:center; color:#666;">Ancien total payé : <span id="ancien-total" data-valeur="<?php echo $commande['total']; ?>"><?php echo number_format($commande['total'], 2, ',', ''); ?> €</span></p>

            <div id="liste-articles">
                <?php foreach ($commande['articles'] as $art) : ?>
                    <div class="article ligne-article"
                         data-id="<?php echo htmlspecialchars($art['id']); ?>"
                         data-prix="<?php echo $art['prix']; ?>">
                        <div class="article-infos">
                            <h3><?php echo htmlspecialchars($art['nom']); ?></h3>
                            <p class="prix-unit"><?php echo number_format($art['prix'], 2, ',', ''); ?> €</p>
                        </div>
                        <div class="article-actions">
                            <button type="button" class="btn-moins">-</button>
                            <span class="qte"><?php echo (int) $art['qte']; ?></span>
                            <button type="button" class="btn-plus">+</button>
                        </div>
                        <p class="prix-total">
                            <span class="sous-total"><?php echo number_format($art['prix'] * $art['qte'], 2, ',', ''); ?></span> €
                        </p>
                        <button type="button" class="supprimer btn-retirer">✕</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="zone-ajout" style="margin: 20px 0;">
                <h3 style="color:#cc7061;">Ajouter un produit</h3>
                <select id="select-ajout">
                    <option value="">Choisir un produit...</option>
                    <?php foreach ($plats as $p) : ?>
                        <option value="<?php echo $p['id']; ?>"
                                data-nom="<?php echo htmlspecialchars($p['nom']); ?>"
                                data-prix="<?php echo $p['prix']; ?>">
                            <?php echo htmlspecialchars($p['nom']); ?>
                            (<?php echo number_format($p['prix'], 2, ',', ''); ?> €)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="btn-ajout">Ajouter</button>
            </div>

            <div id="total">
                <p><strong>Nouveau total :</strong> <span id="nouveau-total"><?php echo number_format($commande['total'], 2, ',', ''); ?></span> €</p>
                <p id="info-difference" style="font-size:0.95rem; color:#666;"></p>
            </div>

            <div id="boutons">
                <a href="Profil.php"><button type="button" id="continuer">Annuler</button></a>
                <button type="button" id="btn-valider-modif">Valider la modification</button>
            </div>

            <p id="message-modif-cmd" style="text-align:center; margin-top:15px;"></p>

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
