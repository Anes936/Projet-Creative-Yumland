<?php
session_start();
require __DIR__ . "/Traitements/securite.php";

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    header("Location: Connexion.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: Accueil.php");
    exit;
}

$idUser = $_GET['id'] ?? '';

$utilisateurs = json_decode(file_get_contents('Data/users.json'), true);

$user = null;
foreach ($utilisateurs as $u) {
    if ($u['id'] === $idUser) {
        $user = $u;
        break;
    }
}

if ($user === null) {
    header("Location: Administrateur.php");
    exit;
}

$commandes = [];
if (file_exists('Data/commandes.json')) {
    $allCommandes = json_decode(file_get_contents('Data/commandes.json'), true);
    foreach ($allCommandes as $cmd) {
        if ($cmd['client'] === $user['identifiant']) {
            $commandes[] = $cmd;
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Profil.css">
    <title>Profil Administrateur <?php echo htmlspecialchars($user['nom']); ?></title>
    <script src="JS/theme.js" defer></script>
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

        <div id="profil">

            <h1>Profil de <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h1>

            <section id="blocinfos">
                <h2>Informations</h2>
                <div id="groupeinfo">
                    <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['nom']); ?></p>
                    <p><strong>Prénom :</strong> <?php echo htmlspecialchars($user['prenom']); ?></p>
                    <p><strong>Email :</strong> <?php echo htmlspecialchars($user['mail']); ?></p>
                    <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($user['tel']); ?></p>
                    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($user['adresse']['numero'] . ' ' . $user['adresse']['rue'] . ', ' . $user['adresse']['postal'] . ' ' . $user['adresse']['ville']); ?></p>
                    <p><strong>Rôle :</strong> <?php echo htmlspecialchars($user['role']); ?></p>
                    <p><strong>Points fidélité :</strong> <?php echo $user['points']; ?> pts</p>
                </div>
            </section>

            <section id="bloccommandes">
                <h2>Commandes de cet utilisateur</h2>
                <?php if (empty($commandes)) : ?>
                    <p>Aucune commande.</p>
                <?php else : ?>
                    <?php foreach ($commandes as $cmd) : ?>
                        <div class="commande">
                            <p><strong><?php echo htmlspecialchars($cmd['id']); ?></strong></p>
                            <p>Total : <?php echo number_format($cmd['total'], 2, ',', ''); ?> €</p>
                            <p>Mode : <?php echo htmlspecialchars($cmd['mode']); ?></p>
                            <p>Statut : <span class="statut <?php echo $cmd['statut'] === 'livree' ? 'livre' : 'attente'; ?>"><?php echo htmlspecialchars($cmd['statut']); ?></span></p>
                            <p>Date : <?php echo htmlspecialchars($cmd['date_commande']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>

            <a href="Administrateur.php"><button id="deconnexion">Retour à la liste</button></a>

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