<?php
session_start();
$plats = json_decode(file_get_contents('Data/plats.json'), true);
$menus = json_decode(file_get_contents('Data/menus.json'), true);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Carte.css">
    <title>Carte</title>
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

    <main id="catalogue">

        <aside id="filtres">

            <div id="blocrecherche">
                <h4>Recherche</h4>
                <input type="search" id="search" placeholder="Chercher...">
            </div>

            <form id="bloccategorie">
                <h4>Catégorie</h4>
                <label><input type="checkbox" name="cat" value="croissanterie"> Croissanterie</label>
                <label><input type="checkbox" name="cat" value="cake"> Gâteaux</label>
                <label><input type="checkbox" name="cat" value="tarte"> Tartes</label>
                <label><input type="checkbox" name="cat" value="cookie"> Cookies</label>
            </form>

            <form id="blocsaveur">
                <h4>Saveurs</h4>
                <label><input type="checkbox" name="flav" value="chocolat"> Chocolat</label>
                <label><input type="checkbox" name="flav" value="vanille"> Vanille</label>
                <label><input type="checkbox" name="flav" value="fruits"> Fruits</label>
                <label><input type="checkbox" name="flav" value="pistache"> Pistache</label>
            </form>

        </aside>

        <section id="blocproduits">

            <div id="blocentete">
                <h1>Nos produits</h1>
                <div id="blocrechercheproduit">
                    <input type="search" placeholder="Rechercher un produit">
                </div>
            </div>

            <div id="blocbox">
    <h2>Nos Box</h2>
    <div id="grillebox">
        <?php foreach ($menus as $menu) : ?>
            <article class="box">
                <h3><?php echo htmlspecialchars($menu['nom']); ?></h3>
                <p class="box-desc"><?php echo htmlspecialchars($menu['description']); ?></p>
                <p class="prix"><?php echo number_format($menu['prix'], 2, ',', ''); ?> €</p>
                <a href="Traitements/traitement_Panier.php?action=ajouterBox&id=<?php echo $menu['id']; ?>">
                    <button class="btn-ajouter">Ajouter au panier</button>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
</div>

            <div id="blocgrille">
                <?php foreach ($plats as $plat) : ?>
                    <article class="gateau">
                        <img src="<?php echo $plat['image']; ?>" alt="<?php echo htmlspecialchars($plat['nom']); ?>">
                        <h3><?php echo htmlspecialchars($plat['nom']); ?></h3>
                        <p class="prix"><?php echo number_format($plat['prix'], 2, ',', ''); ?> €</p>
                        <a href="Traitements/traitement_Panier.php?action=ajouter&id=<?php echo $plat['id']; ?>">
                            <button class="btn-ajouter">Ajouter au panier</button>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>

        </section>

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