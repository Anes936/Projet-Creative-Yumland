<?php
session_start();

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    header("Location: Connexion.php");
    exit;
}

$chemin = 'Data/users.json';
$utilisateurs = json_decode(file_get_contents($chemin), true);

$user = null;
foreach ($utilisateurs as $u) {
    if ($u['identifiant'] === $_SESSION['identifiant']) {
        $user = $u;
        break;
    }
}

if ($user === null) {
    session_destroy();
    header("Location: Connexion.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Profil.css">
    <title>Page de profil</title>
</head>
<body>

    <header>

        <div id="header-left">
            <a href="Panier.html" id="panier">
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

        <div id="profil">

            <h1>Mon profil</h1>

            <section id="blocinfos">
                <h2>Mes informations</h2>
                <div id="groupeinfo">
                    <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['nom']); ?> <button id="modifier">✏️</button></p>
                    <p><strong>Prénom :</strong> <?php echo htmlspecialchars($user['prenom']); ?> <button id="modifier">✏️</button></p>
                    <p><strong>Email :</strong> <?php echo htmlspecialchars($user['mail']); ?> <button id="modifier">✏️</button></p>
                    <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($user['tel']); ?> <button id="modifier">✏️</button></p>
                    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($user['adresse']['numero'] . ' ' . $user['adresse']['rue'] . ', ' . $user['adresse']['postal'] . ' ' . $user['adresse']['ville']); ?> <button id="modifier">✏️</button></p>
                </div>
            </section>

            <section id="bloccommandes">
                <h2>Mes commandes</h2>

                <div id="commande">
                    <p>Commande #12345</p>
                    <p><span id="statut" class="livre">Livré</span></p>
                </div>

                <div id="commande">
                    <p>Commande #12346</p>
                    <p><span id="statut" class="attente">En attente</span></p>
                </div>

                <div id="commande">
                    <p>Commande #12347</p>
                    <p><span id="statut" class="livre">Livré</span></p>
                </div>

            </section>

            <section id="blocfidelite">
                <h2>Mon compte fidélité</h2>
                <p><strong>Points actuels :</strong> <?php echo $user['points']; ?> pts</p>
            </section>

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