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
        <a href="Accueil.html">
            <img src="images/Glogo.png" alt="Logo" >
        </a>
    </div>

    <div id="header-right">
        <a href="Connexion.html">Se connecter</a><span>/</span>
        <a href="Inscription.html">S'inscrire</a>
    </div>

</header>

<main>
    <div id="corps">
         <h1>Inscription</h1>

    <form>
        <label for="nom">Nom:</label>
        <input type="text" id="nom" required ><br/><br/>

        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" required ><br/><br/>

         <label for="date">Date de Naissance:</label>
        <input type="date" id="date" required ><br/><br/>

        <label for="mail">Email:</label>
        <input type="email" id="mail" required ><br/><br/>

        <label for="telephone">N.Telephone(facultatif):</label>
        <input type="tel" id="telephone" "><br/><br/>

        <label for="ville">Ville:</label>
        <input type="text" id="ville" required><br/><br/>

        <label for="postal">Code postal:</label>
        <input type="text" id="postal" required ><br/><br/>

        <label for="rue">Rue:</label>
        <input type="text" id="rue" required ><br/><br/>

        <label for="nrue">N.Rue:</label>
        <input type="text" id="nrue" required ><br/><br/>

        <label for="id">Nom d'utilisateur:</label>
        <input type="text" id="id" required ><br/><br/>

         <label for="emdp">Entrer un mot de passe:</label>
        <input type="password" id="emdp" required ><br/><br/>

         <label for="cmdp">Confirmer le mot de passe:</label>
        <input type="password" id="cmdp" required ><br/><br/>

        <label for="entrer"></label>
        <input type="submit" id="entrer" value="S'inscrire"/><br/><br/>

    </form>


        <a href="Connexion.html" id="dejacompte">Vous avez deja un compte? Cliquez ici pour vous connecter</a>
        
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
