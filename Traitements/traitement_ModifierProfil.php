<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    echo json_encode(['ok' => false, 'message' => 'Non connecte.']);
    exit;
}

$donneesBrutes = file_get_contents('php://input');
$donnees = json_decode($donneesBrutes, true);

if (!is_array($donnees)) {
    echo json_encode(['ok' => false, 'message' => 'Donnees invalides.']);
    exit;
}

$email = trim($donnees['mail'] ?? '');
$tel   = trim($donnees['tel'] ?? '');
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'message' => 'Email invalide.']);
    exit;
}
if ($tel !== '' && !preg_match('/^0[0-9]{9}$/', $tel)) {
    echo json_encode(['ok' => false, 'message' => 'Telephone invalide.']);
    exit;
}

$chemin = '../Data/users.json';
$utilisateurs = json_decode(file_get_contents($chemin), true);

$trouve = false;
foreach ($utilisateurs as &$u) {
    if ($u['identifiant'] === $_SESSION['identifiant']) {
        if (isset($donnees['nom']))    $u['nom']    = trim($donnees['nom']);
        if (isset($donnees['prenom'])) $u['prenom'] = trim($donnees['prenom']);
        if (isset($donnees['mail']))   $u['mail']   = $email;
        if (isset($donnees['tel']))    $u['tel']    = $tel;
        if (isset($donnees['adresse']) && is_array($donnees['adresse'])) {
            if (isset($donnees['adresse']['numero'])) $u['adresse']['numero'] = trim($donnees['adresse']['numero']);
            if (isset($donnees['adresse']['rue']))    $u['adresse']['rue']    = trim($donnees['adresse']['rue']);
            if (isset($donnees['adresse']['ville']))  $u['adresse']['ville']  = trim($donnees['adresse']['ville']);
            if (isset($donnees['adresse']['postal'])) $u['adresse']['postal'] = trim($donnees['adresse']['postal']);
        }
        $trouve = true;
        break;
    }
}
unset($u);

if (!$trouve) {
    echo json_encode(['ok' => false, 'message' => 'Utilisateur introuvable.']);
    exit;
}

file_put_contents(
    $chemin,
    json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

echo json_encode(['ok' => true, 'message' => 'Profil mis a jour.']);
