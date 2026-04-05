<?php
session_start();

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    header("Location: Connexion.php");
    exit;
}

if ($_SESSION['role'] !== 'restaurateur') {
    header("Location: Accueil.php");
    exit;
}

$commandes = [];
if (file_exists('Data/commandes.json')) {
    $commandes = json_decode(file_get_contents('Data/commandes.json'), true);
}

$utilisateurs = json_decode(file_get_contents('Data/users.json'), true);
$livreurs = [];
foreach ($utilisateurs as $u) {
    if ($u['role'] === 'livreur') {
        $livreurs[] = $u;
    }
}

$aPrep = [];
$enCours = [];
$enLivraison = [];
$livrees = [];

foreach ($commandes as $cmd) {
    switch ($cmd['statut']) {
        case 'en_preparation': $aPrep[] = $cmd; break;
        case 'en_cours': $enCours[] = $cmd; break;
        case 'en_livraison': $enLivraison[] = $cmd; break;
        case 'livree': $livrees[] = $cmd; break;
    }
}
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="CommandeRestaurateur.css">
    <title>Gestion des Commandes</title>
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

            <h1>Gestion des Commandes</h1>

            <h2>Commandes à préparer</h2>
            <?php if (empty($aPrep)) : ?>
                <p class="aucune">Aucune commande à préparer.</p>
            <?php else : ?>
                <?php foreach ($aPrep as $cmd) : ?>
                    <div class="commande">
                        <p><strong>Commande :</strong> <?php echo htmlspecialchars($cmd['id']); ?></p>
                        <p><strong>Client :</strong> <?php echo htmlspecialchars($cmd['nom_client']); ?></p>
                        <p><strong>Mode :</strong> <?php echo htmlspecialchars($cmd['mode']); ?></p>
                        <p><strong>Créneau :</strong> <?php echo htmlspecialchars($cmd['creneau']); ?></p>
                        <p><strong>Détails :</strong></p>
                        <ul>
                            <?php foreach ($cmd['articles'] as $art) : ?>
                                <li><?php echo htmlspecialchars($art['nom']); ?> x<?php echo $art['qte']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p><strong>Total :</strong> <?php echo number_format($cmd['total'], 2, ',', ''); ?> €</p>
                        <form action="Traitements/traitement_Restaurateur.php" method="POST" class="form-actions">
                            <input type="hidden" name="id_commande" value="<?php echo $cmd['id']; ?>">
                            <input type="hidden" name="action" value="prete">
                            <select name="livreur">
                                <option value="">Choisir un livreur</option>
                                <?php foreach ($livreurs as $liv) : ?>
                                    <option value="<?php echo $liv['identifiant']; ?>"><?php echo htmlspecialchars($liv['prenom'] . ' ' . $liv['nom']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn-prete">Commande prête</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <h2>Commandes en livraison</h2>
            <?php if (empty($enLivraison)) : ?>
                <p class="aucune">Aucune commande en livraison.</p>
            <?php else : ?>
                <?php foreach ($enLivraison as $cmd) : ?>
                    <div class="commande livraison">
                        <p><strong>Commande :</strong> <?php echo htmlspecialchars($cmd['id']); ?></p>
                        <p><strong>Client :</strong> <?php echo htmlspecialchars($cmd['nom_client']); ?></p>
                        <p><strong>Livreur :</strong> <?php echo htmlspecialchars($cmd['livreur']); ?></p>
                        <p><strong>Total :</strong> <?php echo number_format($cmd['total'], 2, ',', ''); ?> €</p>
                        <p><strong>Statut :</strong> <span class="statut-livraison">En livraison</span></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <h2>Commandes livrées</h2>
            <?php if (empty($livrees)) : ?>
                <p class="aucune">Aucune commande livrée.</p>
            <?php else : ?>
                <?php foreach ($livrees as $cmd) : ?>
                    <div class="commande livree">
                        <p><strong>Commande :</strong> <?php echo htmlspecialchars($cmd['id']); ?></p>
                        <p><strong>Client :</strong> <?php echo htmlspecialchars($cmd['nom_client']); ?></p>
                        <p><strong>Total :</strong> <?php echo number_format($cmd['total'], 2, ',', ''); ?> €</p>
                        <p><strong>Statut :</strong> <span class="statut-livre">Livrée</span></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

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