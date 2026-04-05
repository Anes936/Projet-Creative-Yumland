<?php
session_start();

$plats = json_decode(file_get_contents('Data/plats.json'), true);

$platsParId = [];
foreach ($plats as $p) {
    $platsParId[$p['id']] = $p;
}

$total = 0;
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $id => $qte) {
        if (isset($platsParId[$id])) {
            $total += $platsParId[$id]['prix'] * $qte;
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Panier.css">
    <title>Mon Panier</title>
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

            <h1>Mon Panier</h1>

            <?php if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) : ?>

                <p id="vide">Votre panier est vide.</p>
                <a href="Carte.php"><button id="continuer">Voir la carte</button></a>

            <?php else : ?>

                <?php foreach ($_SESSION['panier'] as $id => $qte) : ?>
                    <?php if (isset($platsParId[$id])) : ?>
                        <?php $plat = $platsParId[$id]; ?>
                        <div class="article">
                            <img src="<?php echo $plat['image']; ?>" alt="<?php echo htmlspecialchars($plat['nom']); ?>">
                            <div class="article-infos">
                                <h3><?php echo htmlspecialchars($plat['nom']); ?></h3>
                                <p class="prix-unit"><?php echo number_format($plat['prix'], 2, ',', ''); ?> €</p>
                            </div>
                            <div class="article-actions">
                                <a href="Traitements/traitement_Panier.php?action=modifier&id=<?php echo $id; ?>&qte=<?php echo $qte - 1; ?>">-</a>
                                <span><?php echo $qte; ?></span>
                                <a href="Traitements/traitement_Panier.php?action=modifier&id=<?php echo $id; ?>&qte=<?php echo $qte + 1; ?>">+</a>
                            </div>
                            <p class="prix-total"><?php echo number_format($plat['prix'] * $qte, 2, ',', ''); ?> €</p>
                            <a href="Traitements/traitement_Panier.php?action=supprimer&id=<?php echo $id; ?>" class="supprimer">✕</a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <div id="total">
                    <p><strong>Total :</strong> <?php echo number_format($total, 2, ',', ''); ?> €</p>
                </div>

                <div id="boutons">
                    <a href="Carte.php"><button id="continuer">Continuer mes achats</button></a>
                    <a href="Commandes.php"><button id="valider">Valider ma commande</button></a>
                </div>

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