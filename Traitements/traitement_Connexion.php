<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Connexion.php");
    exit;
}

// Récupérer les données
$identifiant = trim($_POST['identifiant']);
$mdp         = $_POST['mdp'];

// Lire le fichier JSON
$chemin = '../Data/users.json';

if (!file_exists($chemin)) {
    echo "Aucun utilisateur enregistré.";
    exit;
}

$utilisateurs = json_decode(file_get_contents($chemin), true);

// Chercher l'utilisateur dans le tableau
$utilisateur_trouve = null;

foreach ($utilisateurs as $user) {
    // On accepte connexion par email OU par username
    if ($user['mail'] === $identifiant || $user['identifiant'] === $identifiant) {
        $utilisateur_trouve = $user;
        break;
    }
}

// Est-ce que l'utilisateur existe ?
if ($utilisateur_trouve === null) {
    echo "Identifiant\Email introuvable.";
    exit;
}

// Est-ce que le mot de passe est correct ?
// password_verify() compare le mot de passe tapé avec le hash stocké
if (!password_verify($mdp, $utilisateur_trouve['mdp'])) {
    echo "Mot de passe incorrect.";
    exit;
}

// ── Tout est bon → créer la session ──
$_SESSION['connecte'] = true;
$_SESSION['nom']      = $utilisateur_trouve['nom'];
$_SESSION['prenom']   = $utilisateur_trouve['prenom'];
$_SESSION['mail']     = $utilisateur_trouve['mail'];
$_SESSION['identifiant'] = $utilisateur_trouve['identifiant'];
$_SESSION['role']     = $utilisateur_trouve['role'];

// Rediriger vers le profil
header("Location: ../Profil.php");
exit;
?>