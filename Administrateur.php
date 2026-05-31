<?php
// Espace administrateur : liste des utilisateurs et blocage des comptes.
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

$utilisateurs = json_decode(file_get_contents('Data/users.json'), true);
$commandes = [];
if (file_exists('Data/commandes.json')) {
    $commandes = json_decode(file_get_contents('Data/commandes.json'), true);
}

function compterCommandes($identifiant, $commandes) {
    $count = 0;
    foreach ($commandes as $cmd) {
        if ($cmd['client'] === $identifiant) {
            $count++;
        }
    }
    return $count;
}
?>
<!doctype html>
<html>
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="images/logo.png"/>
    <link rel="apple-touch-icon" sizes="32x32" href="images/logo.png">
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Administrateur.css">
    <title>Page Administrateur</title>
    <script src="JS/theme.js" defer></script>
    <script src="JS/recherche.js" defer></script>
    <script src="JS/admin.js" defer></script>
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

            <h1>Espace Administrateur</h1>

            <h2>Liste des utilisateurs</h2>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Commandes</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($utilisateurs as $u) : ?>
                    <?php $estBloque = !empty($u['bloque']); ?>
                    <tr data-id-user="<?php echo htmlspecialchars($u['id']); ?>">
                        <td><?php echo htmlspecialchars($u['id']); ?></td>
                        <td><?php echo htmlspecialchars($u['nom'] . ' ' . $u['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($u['mail']); ?></td>
                        <td><?php echo htmlspecialchars($u['role']); ?></td>
                        <td><?php echo compterCommandes($u['identifiant'], $commandes); ?></td>
                        <td>
                            <a href="ProfilAdmin.php?id=<?php echo $u['id']; ?>" class="btn-voir">Voir le profil</a>
                            <button class="btn-bloquer"
                                    data-id="<?php echo htmlspecialchars($u['id']); ?>"
                                    data-bloque="<?php echo $estBloque ? '1' : '0'; ?>">
                                <?php echo $estBloque ? 'Débloquer' : 'Bloquer'; ?>
                            </button>
                            <span class="etat-bloque" style="margin-left:8px; font-size:0.85rem; color:#c0392b;">
                                <?php echo $estBloque ? '(bloqué)' : ''; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

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