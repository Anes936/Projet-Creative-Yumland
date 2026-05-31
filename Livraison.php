<?php
// Espace livreur : détails de la commande assignée et validation de la livraison.
session_start();
require __DIR__ . "/Traitements/securite.php";

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    header("Location: Connexion.php");
    exit;
}

if ($_SESSION['role'] !== 'livreur') {
    header("Location: Accueil.php");
    exit;
}

$commandes = [];
if (file_exists('Data/commandes.json')) {
    $allCommandes = json_decode(file_get_contents('Data/commandes.json'), true);
    foreach ($allCommandes as $cmd) {
        if ($cmd['livreur'] === $_SESSION['identifiant'] && $cmd['statut'] === 'en_livraison') {
            $commandes[] = $cmd;
        }
    }
}

$utilisateurs = json_decode(file_get_contents('Data/users.json'), true);
$clients = [];
foreach ($utilisateurs as $u) {
    $clients[$u['identifiant']] = $u;
}
?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Livraison.css">
    <title>Mes Livraisons</title>
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
    <?php if (isset($_SESSION['connecte']) && $_SESSION['connecte']) : ?>
        <?php if ($_SESSION['role'] === 'admin') : ?>
            <a href="Administrateur.php" class="btn-espace">Espace Admin</a>
        <?php elseif ($_SESSION['role'] === 'restaurateur') : ?>
            <a href="CommandeRestaurateur.php" class="btn-espace">Espace Restaurateur</a>
        <?php elseif ($_SESSION['role'] === 'livreur') : ?>
            <a href="Livraison.php" class="btn-espace">Espace Livreur</a>
        <?php endif; ?>
        <a href="Profil.php">
            <img src="images/IconeProfil.png" alt="Mon profil">
        </a>
    <?php else : ?>
        <a href="Connexion.php">Se connecter</a>
        <span>/</span>
        <a href="Inscription.php">S'inscrire</a>
    <?php endif; ?>
</div>

    </header>

    <main>
        <div id="corps">

            <h1>Mes Livraisons</h1>

            <?php if (empty($commandes)) : ?>
                <p class="aucune">Aucune livraison en cours.</p>
            <?php else : ?>
                <?php foreach ($commandes as $cmd) : ?>
                    <?php $client = $clients[$cmd['client']] ?? null; ?>
                    <div class="livraison">

                        <p class="id-commande"><?php echo htmlspecialchars($cmd['id']); ?></p>

                        <div class="info">
                            <span class="label">Client</span>
                            <span class="valeur"><?php echo htmlspecialchars($cmd['nom_client']); ?></span>
                        </div>

                        <?php if ($client) : ?>
                            <div class="info">
                                <span class="label">Téléphone</span>
                                <span class="valeur"><?php echo htmlspecialchars($client['tel']); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="info">
                            <span class="label">Adresse</span>
                            <span class="valeur"><?php echo htmlspecialchars($cmd['adresse']['numero'] . ' ' . $cmd['adresse']['rue'] . ', ' . $cmd['adresse']['postal'] . ' ' . $cmd['adresse']['ville']); ?></span>
                        </div>

                        <div class="info">
                            <span class="label">Créneau</span>
                            <span class="valeur"><?php echo htmlspecialchars($cmd['creneau']); ?></span>
                        </div>

                        <div class="info">
                            <span class="label">Détails</span>
                            <span class="valeur">
                                <?php foreach ($cmd['articles'] as $art) : ?>
                                    <?php echo htmlspecialchars($art['nom']); ?> x<?php echo $art['qte']; ?><br>
                                <?php endforeach; ?>
                            </span>
                        </div>

                        <div class="info">
                            <span class="label">Total</span>
                            <span class="valeur"><?php echo number_format($cmd['total'], 2, ',', ''); ?> €</span>
                        </div>

                        <?php
                        $adresseComplete = $cmd['adresse']['numero'] . ' ' . $cmd['adresse']['rue'] . ', ' . $cmd['adresse']['postal'] . ' ' . $cmd['adresse']['ville'];
                        $adresseURL = urlencode($adresseComplete);
                        ?>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $adresseURL; ?>" target="_blank" class="btn-maps">Ouvrir dans Maps</a>

                        <form action="Traitements/traitement_Livraison.php" method="POST">
                            <input type="hidden" name="id_commande" value="<?php echo $cmd['id']; ?>">
                            <button type="submit" name="action" value="livree" class="btn-livre">Commande livrée</button>
                            <button type="submit" name="action" value="abandonnee" class="btn-abandon">Adresse introuvable</button>
                        </form>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

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