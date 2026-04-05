<?php
session_start();

$idCommande = $_GET['id'] ?? '';
$commande = null;

if ($idCommande !== '' && isset($_SESSION['connecte']) && $_SESSION['connecte']) {
    if (file_exists('Data/commandes.json')) {
        $commandes = json_decode(file_get_contents('Data/commandes.json'), true);
        foreach ($commandes as $cmd) {
            if ($cmd['id'] === $idCommande && $cmd['client'] === $_SESSION['identifiant']) {
                $commande = $cmd;
                break;
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="Commun.css">
    <link rel="stylesheet" href="Avis.css">
    <title>Avis</title>
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
        <div id="avistitre">

            <?php if ($commande !== null) : ?>

                <h1>Noter ma commande</h1>
                <p>Commande <?php echo htmlspecialchars($commande['id']); ?> du <?php echo htmlspecialchars($commande['date_commande']); ?></p>

                <form id="aviscorps" action="Traitements/traitement_Notation.php" method="POST">

                    <input type="hidden" name="id_commande" value="<?php echo $commande['id']; ?>">

                    <div id="formulaire">
                        <label for="note_livraison">Note de la livraison :</label>
                        <select id="note_livraison" name="note_livraison" required>
                            <option value="">Choisissez une note</option>
                            <option value="5">Excellent</option>
                            <option value="4">Très bon</option>
                            <option value="3">Bon</option>
                            <option value="2">Moyen</option>
                            <option value="1">Mauvais</option>
                        </select>
                    </div>

                    <div id="formulaire">
                        <label for="note_produits">Note des produits :</label>
                        <select id="note_produits" name="note_produits" required>
                            <option value="">Choisissez une note</option>
                            <option value="5">Excellent</option>
                            <option value="4">Très bon</option>
                            <option value="3">Bon</option>
                            <option value="2">Moyen</option>
                            <option value="1">Mauvais</option>
                        </select>
                    </div>

                    <div id="formulaire">
                        <label for="commentaire">Commentaire (facultatif) :</label>
                        <input type="text" id="commentaire" name="commentaire" placeholder="Qu'avez-vous pensé de votre commande ?">
                    </div>

                    <button type="submit" id="boutonavis">Envoyer ma note</button>

                </form>

            <?php else : ?>

                <h1>Donnez nous votre avis</h1>
                <p>Votre avis nous est précieux. N'hésitez pas à nous faire part de vos commentaires !</p>

                <form id="aviscorps">

                    <div id="formulaire">
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom" name="nom" required>
                    </div>

                    <div id="formulaire">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div id="formulaire">
                        <label for="note">Note :</label>
                        <select id="note" name="note" required>
                            <option value="">Choisissez une note</option>
                            <option value="5">Excellent</option>
                            <option value="4">Très bon</option>
                            <option value="3">Bon</option>
                            <option value="2">Moyen</option>
                            <option value="1">Mauvais</option>
                        </select>
                    </div>

                    <div id="formulaire">
                        <label for="avis">Votre avis :</label>
                        <input type="text" id="avis" name="avis" placeholder="Qu'en avez-vous pensé ? (facultatif)">
                    </div>

                    <button type="submit" id="boutonavis">Envoyer</button>

                </form>

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