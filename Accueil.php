<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="images/logo.png"/>
    <link rel="apple-touch-icon" sizes="32x32" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="Acceuil.css"/>
    <link rel="stylesheet" href="Commun.css">
    <title>Page d'acceuil</title>
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

        <h1>L'instant Fondant</h1>
        <h2>La patisserie qui fait fondre vos envies.</h2>

        <h3 id="titre">Nos best sellers</h3>

        <section id="corps">
            <div class="gateau1">
                <img src="images/Fondantcb.jpeg" alt="Fondant au chocolat blanc">
                <div class="ngateau1">Fondant au chocolat blanc</div>
            </div>
            <div class="gateau2">
                <img src="images/Flan.jpg" alt="Flan vanille">
                <div class="ngateau2">Flan vanille</div>
            </div>
            <div class="gateau3">
                <img src="images/Tartefraises.jpg" alt="Tarte aux fraises">
                <div class="ngateau3">Tarte aux fraises</div>
            </div>
        </section>

        <div class="Bouton">
            <a href="Carte.php"><button id="decouvrir">decouvrir nos patisseries</button></a>
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