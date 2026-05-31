<?php
session_start();
// Formulaire de connexion (identifiant ou email + mot de passe).
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="Connexion.css"/>
    <link rel="stylesheet" href="Commun.css">
    <title>Page de Connexion</title>
    <script src="JS/theme.js" defer></script>
    <script src="JS/recherche.js" defer></script>
    <script src="JS/validation.js" defer></script>
    <script src="JS/compteur.js" defer></script>
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

    <main>
        <div id="corps">

            <h1>Connexion</h1>

            <form action="Traitements/traitement_Connexion.php" method="POST">
                <input type="text" id="identifiant" name="identifiant" required placeholder="Email/Identifiant">
                <input type="password" id="mdp" name="mdp" required placeholder="Mot de passe">
                <input type="submit" id="Connexion" value="Se connecter">
            </form>

            <a href="Inscription.php">Vous n'avez pas encore de compte ? Inscrivez vous ici</a>

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