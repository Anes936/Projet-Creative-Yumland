<?php
// Page produits (la carte) : box, plats, filtres, tri et recherche.
session_start();
require __DIR__ . "/Traitements/securite.php";
$plats = json_decode(file_get_contents('Data/plats.json'), true);
$menus = json_decode(file_get_contents('Data/menus.json'), true);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Carte.css">
    <title>Carte</title>
    <script src="JS/theme.js" defer></script>
    <script src="JS/carte.js" defer></script>
</head>
<body>

    <header>

        <div id="header-left">
            <a href="Panier.php" id="panier">
                <img src="images/Panier.jpeg" alt="Panier">
            </a>
            <div id="zone-recherche">
                <form id="form-recherche">
                    <label for="recherche"></label>
                    <input type="text" id="recherche" autocomplete="off" placeholder="Qu'est ce qui vous ferait plaisir">
                    <button type="submit">
                        <img src="images/Rcherche.jpeg" alt="Loupe"/>
                    </button>
                </form>
                <ul id="suggestions"></ul>
            </div>
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

    <main id="catalogue">

        <aside id="filtres">

            <form id="bloccategorie">
                <h4>Catégorie</h4>
                <label><input type="checkbox" class="filtre-categorie" value="viennoiseries"> Viennoiseries</label>
                <label><input type="checkbox" class="filtre-categorie" value="patisseries"> Pâtisseries</label>
                <label><input type="checkbox" class="filtre-categorie" value="tartes"> Tartes</label>
                <label><input type="checkbox" class="filtre-categorie" value="cookies"> Cookies</label>
            </form>

            <form id="blocgout">
                <h4>Goûts</h4>
                <label><input type="checkbox" class="filtre-gout" value="chocolat"> Chocolat</label>
                <label><input type="checkbox" class="filtre-gout" value="fruit"> Fruit</label>
                <label><input type="checkbox" class="filtre-gout" value="praline"> Praliné</label>
                <label><input type="checkbox" class="filtre-gout" value="vanille"> Vanille</label>
            </form>

        </aside>

        <section id="blocproduits">

            <div id="blocentete">
                <h1>Nos produits</h1>
                <div id="bloctri">
                    <label for="tri">Trier par :</label>
                    <select id="tri">
                        <option value="defaut">Par défaut</option>
                        <option value="prix_croissant">Prix croissant</option>
                        <option value="prix_decroissant">Prix décroissant</option>
                        <option value="plus_commandes">Les plus commandés</option>
                    </select>
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
                    <article class="gateau"
                             data-id="<?php echo htmlspecialchars($plat['id']); ?>"
                             data-prix="<?php echo $plat['prix']; ?>"
                             data-commandes="<?php echo $plat['nb_commandes']; ?>">
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