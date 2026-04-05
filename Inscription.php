<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="Inscription.css"/>
    <link rel="stylesheet" href="Commun.css">
    <title>Page d'Inscription</title>
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

            <h1>Inscription</h1>

            <form action="Traitements/traitement_Inscription.php" method="POST">

                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required><br/><br/>

                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" required><br/><br/>

                <label for="date">Date de Naissance:</label>
                <input type="date" id="date" name="date" required><br/><br/>

                <label for="mail">Email:</label>
                <input type="email" id="mail" name="mail" required><br/><br/>

                <label for="telephone">N.Telephone (facultatif):</label>
                <input type="tel" id="telephone" name="telephone"><br/><br/>

                <label for="ville">Ville:</label>
                <input type="text" id="ville" name="ville" required><br/><br/>

                <label for="postal">Code postal:</label>
                <input type="text" id="postal" name="postal" required><br/><br/>

                <label for="rue">Rue:</label>
                <input type="text" id="rue" name="rue" required><br/><br/>

                <label for="nrue">N.Rue:</label>
                <input type="text" id="nrue" name="nrue" required><br/><br/>

                <label for="identifiant">Nom d'utilisateur:</label>
                <input type="text" id="identifiant" name="identifiant" required><br/><br/>

                <label for="emdp">Entrer un mot de passe:</label>
                <input type="password" id="emdp" name="emdp" required><br/><br/>

                <label for="cmdp">Confirmer le mot de passe:</label>
                <input type="password" id="cmdp" name="cmdp" required><br/><br/>

                <input type="submit" id="entrer" value="S'inscrire"/>

            </form>

            <a href="Connexion.php" id="dejacompte">Vous avez deja un compte ? Cliquez ici pour vous connecter</a>

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