<?php session_start(); ?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="Connexion.css"/>
        <link rel="stylesheet" href="Commun.css">
        <title>Page de Connexion</title>
    </head>

    <body>
           <header>
    
    <div id="header-left">
        <a href="Panier.html" id="panier">
            <img src="images/Panier.jpeg" alt="Panier" >
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
            <img src="images/Glogo.png" alt="Logo" >
        </a>
    </div>

<div id="header-right">

    <?php if (isset($_SESSION['connecte']) && $_SESSION['connecte']) : ?>
        <a href="Profil.php">
          <img src="images/IconeProfil.png" alt="Mon profil">
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
         <input type="text" id="identifiant" name="identifiant" required placeholder="Email\Identifiant">
         <input type="password" id="mdp" name="mdp" required placeholder="Mot de passe">
         <input type="submit" id="Connexion" value="Se connecter">
        </form>

        <a href="Inscription.html">Vous n'avez pas encore de compte ? Inscrivez vous ici</a>
    </div>

</main>

<footer>
    <div id="footer-left">
        <img src="images/logo.png" alt="logo">
    </div>
    <div id="footer-right">
        <a href="Avis.html">Donnez nous votre avis! </a>
    </div>
</footer>

    </body>
</html>
