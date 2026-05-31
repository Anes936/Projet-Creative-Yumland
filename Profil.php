<?php
// Profil du client : informations, historique des commandes, fidélité et notation.
session_start();
require __DIR__ . "/Traitements/securite.php";

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

$commandes = [];
if (file_exists('Data/commandes.json')) {
    $allCommandes = json_decode(file_get_contents('Data/commandes.json'), true);
    foreach ($allCommandes as $cmd) {
        if ($cmd['client'] === $_SESSION['identifiant']) {
            $commandes[] = $cmd;
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="images/logo.png"/>
    <link rel="apple-touch-icon" sizes="32x32" href="images/logo.png">
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Profil.css">
    <title>Page de profil</title>
    <script src="JS/theme.js" defer></script>
    <script src="JS/recherche.js" defer></script>
    <script src="JS/validation.js" defer></script>
    <script src="JS/compteur.js" defer></script>
    <script src="JS/profil.js" defer></script>
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

        <div id="profil">

            <h1>Mon profil</h1>

            <section id="blocinfos">
                <h2>Mes informations</h2>
                <div id="groupeinfo">
                    <p><strong>Nom :</strong> <span class="valeur-info" data-champ="nom"><?php echo htmlspecialchars($user['nom']); ?></span></p>
                    <p><strong>Prénom :</strong> <span class="valeur-info" data-champ="prenom"><?php echo htmlspecialchars($user['prenom']); ?></span></p>
                    <p><strong>Email :</strong> <span class="valeur-info" data-champ="mail"><?php echo htmlspecialchars($user['mail']); ?></span></p>
                    <p><strong>Téléphone :</strong> <span class="valeur-info" data-champ="tel"><?php echo htmlspecialchars($user['tel']); ?></span></p>
                    <p><strong>Adresse :</strong> <span class="valeur-info" data-champ="adresse"><?php echo htmlspecialchars($user['adresse']['numero'] . ' ' . $user['adresse']['rue'] . ', ' . $user['adresse']['postal'] . ' ' . $user['adresse']['ville']); ?></span></p>
                </div>
                <button id="btn-modifier-infos">Modifier mes informations</button>
                <p id="message-modif"></p>
            </section>

            <section id="bloccommandes">
                <h2>Mes commandes</h2>

                <?php if (empty($commandes)) : ?>
                    <p>Aucune commande pour le moment.</p>
                <?php else : ?>
                    <?php foreach ($commandes as $cmd) : ?>
                        <div class="commande">
                            <p><strong><?php echo htmlspecialchars($cmd['id']); ?></strong></p>
                            <p>Total : <?php echo number_format($cmd['total'], 2, ',', ''); ?> €</p>
                            <p>Mode : <?php echo htmlspecialchars($cmd['mode']); ?></p>
                            <p>Date : <?php echo htmlspecialchars($cmd['date_commande']); ?></p>
                            <p>
                                <span class="statut <?php
                                    if ($cmd['statut'] === 'livree') echo 'livre';
                                    elseif ($cmd['statut'] === 'en_livraison') echo 'attente';
                                    elseif ($cmd['statut'] === 'en_preparation') echo 'attente';
                                    elseif ($cmd['statut'] === 'payee') echo 'attente';
                                    elseif ($cmd['statut'] === 'abandonnee') echo 'attente';
                                ?>">
                                    <?php
                                        if ($cmd['statut'] === 'payee') echo 'Payée';
                                        elseif ($cmd['statut'] === 'en_preparation') echo 'En préparation';
                                        elseif ($cmd['statut'] === 'en_livraison') echo 'En livraison';
                                        elseif ($cmd['statut'] === 'livree') echo 'Livrée';
                                        elseif ($cmd['statut'] === 'abandonnee') echo 'Abandonnée';
                                    ?>
                                </span>
                            </p>
                            <?php if ($cmd['statut'] === 'payee') : ?>
                                <a href="ModifierCommande.php?id=<?php echo $cmd['id']; ?>" class="btn-noter">Modifier cette commande</a>
                            <?php endif; ?>
                            <?php if ($cmd['statut'] === 'livree') : ?>
                                <?php if (!empty($cmd['note'])) : ?>
                                    <p class="deja-note">Vous avez noté cette commande : <?php echo (int) $cmd['note']['note_produits']; ?>/5</p>
                                <?php else : ?>
                                    <a href="Avis.php?id=<?php echo $cmd['id']; ?>" class="btn-noter">Noter cette commande</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </section>

            <section id="blocfidelite">
                <h2>Mon compte fidélité</h2>
                <p><strong>Points actuels :</strong> <?php echo $user['points']; ?> pts</p>
            </section>

            <a href="Traitements/traitement_Deconnexion.php">
                <button id="deconnexion">Se déconnecter</button>
            </a>

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