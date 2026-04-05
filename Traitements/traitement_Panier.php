<?php
session_start();

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$action = $_GET['action'] ?? '';
$id     = $_GET['id'] ?? '';

if ($action === 'ajouter' && $id !== '') {
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id] += 1;
    } else {
        $_SESSION['panier'][$id] = 1;
    }
    header("Location: ../Carte.php");
    exit;
}

if ($action === 'ajouterBox' && $id !== '') {
    $menus = json_decode(file_get_contents('../Data/menus.json'), true);
    foreach ($menus as $menu) {
        if ($menu['id'] === $id) {
            foreach ($menu['plats'] as $platId) {
                if (isset($_SESSION['panier'][$platId])) {
                    $_SESSION['panier'][$platId] += 1;
                } else {
                    $_SESSION['panier'][$platId] = 1;
                }
            }
            break;
        }
    }
    header("Location: ../Carte.php");
    exit;
}

if ($action === 'supprimer' && $id !== '') {
    unset($_SESSION['panier'][$id]);
    header("Location: ../Panier.php");
    exit;
}

if ($action === 'modifier' && $id !== '') {
    $qte = intval($_GET['qte'] ?? 1);
    if ($qte <= 0) {
        unset($_SESSION['panier'][$id]);
    } else {
        $_SESSION['panier'][$id] = $qte;
    }
    header("Location: ../Panier.php");
    exit;
}

header("Location: ../Carte.php");
exit;
?>