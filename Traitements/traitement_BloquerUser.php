<?php
// Admin : bloque ou débloque un compte (appelé en asynchrone par admin.js).
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['connecte']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['ok' => false, 'message' => 'Reserve a l\'administrateur.']);
    exit;
}

$donnees = json_decode(file_get_contents('php://input'), true);
$idUser  = $donnees['id'] ?? '';
$bloquer = !empty($donnees['bloquer']);

if ($idUser === '') {
    echo json_encode(['ok' => false, 'message' => 'Id manquant.']);
    exit;
}

$chemin = '../Data/users.json';
$utilisateurs = json_decode(file_get_contents($chemin), true);

$trouve = false;
foreach ($utilisateurs as &$u) {
    if ($u['id'] === $idUser) {
        if ($u['identifiant'] === $_SESSION['identifiant']) {
            echo json_encode(['ok' => false, 'message' => 'Vous ne pouvez pas vous bloquer vous-meme.']);
            exit;
        }
        $u['bloque'] = $bloquer;
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

echo json_encode([
    'ok' => true,
    'bloque' => $bloquer,
    'message' => $bloquer ? 'Utilisateur bloque.' : 'Utilisateur debloque.'
]);
