<?php
// Renvoie en JSON les plats qui correspondent aux filtres (catégorie, goûts) et à la recherche.
header('Content-Type: application/json; charset=utf-8');

$plats = json_decode(file_get_contents('../Data/plats.json'), true);

$categories = isset($_GET['categories']) && $_GET['categories'] !== '' ? explode(',', $_GET['categories']) : [];
$gouts      = isset($_GET['gouts'])      && $_GET['gouts']      !== '' ? explode(',', $_GET['gouts'])      : [];
$saveurs    = isset($_GET['saveurs'])    && $_GET['saveurs']    !== '' ? explode(',', $_GET['saveurs'])    : [];
$recherche  = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';

$resultat = [];

foreach ($plats as $plat) {

    // Recherche : on garde les plats dont le nom "commence par" le texte tapé (insensible à la casse).
    if ($recherche !== '' && stripos($plat['nom'], $recherche) !== 0) {
        continue;
    }

    if (!empty($categories) && !in_array($plat['categorie'], $categories, true)) {
        continue;
    }

    if (!empty($gouts)) {
        $goutsPlat = $plat['gouts'] ?? [];
        $trouve = false;
        foreach ($gouts as $g) {
            if (in_array($g, $goutsPlat, true)) {
                $trouve = true;
                break;
            }
        }
        if (!$trouve) continue;
    }

    if (!empty($saveurs) && !in_array($plat['saveur'], $saveurs, true)) {
        continue;
    }

    $resultat[] = $plat;
}

echo json_encode($resultat, JSON_UNESCAPED_UNICODE);
