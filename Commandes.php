<?php
session_start();

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    header("Location: Connexion.php");
    exit;
}

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    header("Location: Carte.php");
    exit;
}

$plats = json_decode(file_get_contents('Data/plats.json'), true);
$platsParId = [];
foreach ($plats as $p) {
    $platsParId[$p['id']] = $p;
}

$utilisateurs = json_decode(file_get_contents('Data/users.json'), true);
$user = null;
foreach ($utilisateurs as $u) {
    if ($u['identifiant'] === $_SESSION['identifiant']) {
        $user = $u;
        break;
    }
}

$total = 0;
foreach ($_SESSION['panier'] as $id => $qte) {
    if (isset($platsParId[$id])) {
        $total += $platsParId[$id]['prix'] * $qte;
    }
}
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Commandes.css">
    <title>Valider ma commande</title>
</head>
<body>

    <header>

        <div id="header-left">
            <a href="Panier.php" id="panier">
                <img src="images/Panier.jpeg" alt="Panier">
            </a>
            <form>
                <label for="recherche"></label>
                <input type="text" id="recherche" placeholder="Qu'est ce qui vous ferait plaisir">
                <button>
                    <img src="images/Rcherche.jpeg" alt="Loupe"/>
                </button>
            </form>
        </div>

        <div id="header-center">
            <a href="Accueil.php">
                <img src="images/Glogo.png" alt="Logo">
            </a>
        </div>

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

            <h1>Valider ma commande</h1>

            <section id="resume">
                <h2>Résumé</h2>
                <?php foreach ($_SESSION['panier'] as $id => $qte) : ?>
                    <?php if (isset($platsParId[$id])) : ?>
                        <?php $plat = $platsParId[$id]; ?>
                        <div class="ligne">
                            <span><?php echo htmlspecialchars($plat['nom']); ?> x<?php echo $qte; ?></span>
                            <span><?php echo number_format($plat['prix'] * $qte, 2, ',', ''); ?> €</span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="ligne total">
                    <span><strong>Total</strong></span>
                    <span><strong><?php echo number_format($total, 2, ',', ''); ?> €</strong></span>
                </div>
            </section>

            <form action="Traitements/traitement_Commandes.php" method="POST">

                <section id="mode">
                    <h2>Mode de récupération</h2>
                    <label><input type="radio" name="mode" value="livraison" checked> Livraison</label>
                    <label><input type="radio" name="mode" value="emporter"> À emporter</label>
                    <label><input type="radio" name="mode" value="surplace"> Sur place</label>
                </section>

                <section id="adresse">
                    <h2>Adresse de livraison</h2>
                    <p><?php echo htmlspecialchars($user['adresse']['numero'] . ' ' . $user['adresse']['rue'] . ', ' . $user['adresse']['postal'] . ' ' . $user['adresse']['ville']); ?></p>
                </section>

                <section id="creneau">
                    <h2>Créneau</h2>
                    <label><input type="radio" name="creneau" value="maintenant" checked> Dès que possible</label>
                    <label><input type="radio" name="creneau" value="plusTard"> Choisir une date et heure</label>
                    <div id="dateheure">
                        <input type="date" name="date">
                        <input type="time" name="heure">
                    </div>
                </section>

                <button type="submit" id="confirmer">Confirmer la commande</button>

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