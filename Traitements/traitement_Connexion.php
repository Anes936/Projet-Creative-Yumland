<?php
// Connexion : on vérifie l'identifiant/email et le mot de passe, puis on ouvre la session.
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Connexion.php");
    exit;
}

$identifiant = trim($_POST['identifiant']);
$mdp         = $_POST['mdp'];

$chemin = '../Data/users.json';

if (!file_exists($chemin)) {
    echo "Aucun utilisateur enregistré.";
    exit;
}

$utilisateurs = json_decode(file_get_contents($chemin), true);

$utilisateur_trouve = null;

foreach ($utilisateurs as $user) {
    if ($user['mail'] === $identifiant || $user['identifiant'] === $identifiant) {
        $utilisateur_trouve = $user;
        break;
    }
}

if ($utilisateur_trouve === null) {
    echo "Identifiant/Email introuvable.";
    exit;
}

// Le mot de passe est haché en base, on le compare avec password_verify.
if (!password_verify($mdp, $utilisateur_trouve['mdp'])) {
    echo "Mot de passe incorrect.";
    exit;
}

$_SESSION['connecte']    = true;
$_SESSION['nom']         = $utilisateur_trouve['nom'];
$_SESSION['prenom']      = $utilisateur_trouve['prenom'];
$_SESSION['mail']        = $utilisateur_trouve['mail'];
$_SESSION['identifiant'] = $utilisateur_trouve['identifiant'];
$_SESSION['role']        = $utilisateur_trouve['role'];

header("Location: ../Profil.php");
exit;
?>