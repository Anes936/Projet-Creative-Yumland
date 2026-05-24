<?php
session_start();
require __DIR__ . "/Traitements/securite.php";

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

$payees      = [];
$enPrep      = [];
$pretes      = [];
$enLivraison = [];
$livrees     = [];

foreach ($commandes as $cmd) {
    switch ($cmd['statut']) {
        case 'payee':          $payees[]      = $cmd; break;
        case 'en_preparation': $enPrep[]      = $cmd; break;
        case 'prete':          $pretes[]      = $cmd; break;
        case 'en_livraison':   $enLivraison[] = $cmd; break;
        case 'livree':         $livrees[]     = $cmd; break;
    }
}
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="CommandeRestaurateur.css">
    <title>Gestion des Commandes</title>
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
        <div id="corps">

            <h1>Gestion des Commandes</h1>

            <h2>Commandes payées (à démarrer)</h2>
            <?php if (empty($payees)) : ?>
                <p class="aucune">Aucune commande payée en attente.</p>
            <?php else : ?>
                <?php foreach ($payees as $cmd) : ?>
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
                            <input type="hidden" name="action" value="demarrer">
                            <button type="submit" class="btn-prete">Démarrer la préparation</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <h2>Commandes en préparation</h2>
            <?php if (empty($enPrep)) : ?>
                <p class="aucune">Aucune commande en préparation.</p>
            <?php else : ?>
                <?php foreach ($enPrep as $cmd) : ?>
                    <div class="commande">
                        <p><strong>Commande :</strong> <?php echo htmlspecialchars($cmd['id']); ?></p>
                        <p><strong>Client :</strong> <?php echo htmlspecialchars($cmd['nom_client']); ?></p>
                        <p><strong>Mode :</strong> <?php echo htmlspecialchars($cmd['mode']); ?></p>
                        <p><strong>Total :</strong> <?php echo number_format($cmd['total'], 2, ',', ''); ?> €</p>
                        <form action="Traitements/traitement_Restaurateur.php" method="POST" class="form-actions">
                            <input type="hidden" name="id_commande" value="<?php echo $cmd['id']; ?>">
                            <input type="hidden" name="action" value="prete">
                            <button type="submit" class="btn-prete">Marquer comme prête</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <h2>Commandes prêtes (à assigner à un livreur)</h2>
            <?php if (empty($pretes)) : ?>
                <p class="aucune">Aucune commande prête à livrer.</p>
            <?php else : ?>
                <?php foreach ($pretes as $cmd) : ?>
                    <div class="commande">
                        <p><strong>Commande :</strong> <?php echo htmlspecialchars($cmd['id']); ?></p>
                        <p><strong>Client :</strong> <?php echo htmlspecialchars($cmd['nom_client']); ?></p>
                        <p><strong>Total :</strong> <?php echo number_format($cmd['total'], 2, ',', ''); ?> €</p>
                        <form action="Traitements/traitement_Restaurateur.php" method="POST" class="form-actions">
                            <input type="hidden" name="id_commande" value="<?php echo $cmd['id']; ?>">
                            <input type="hidden" name="action" value="assigner">
                            <select name="livreur" required>
                                <option value="">Choisir un livreur</option>
                                <?php foreach ($livreurs as $liv) : ?>
                                    <option value="<?php echo $liv['identifiant']; ?>"><?php echo htmlspecialchars($liv['prenom'] . ' ' . $liv['nom']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn-prete">Assigner et lancer la livraison</button>
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