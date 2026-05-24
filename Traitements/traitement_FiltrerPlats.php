<?php
header('Content-Type: application/json; charset=utf-8');

$plats = json_decode(file_get_contents('../Data/plats.json'), true);

$categories = isset($_GET['categories']) && $_GET['categories'] !== '' ? explode(',', $_GET['categories']) : [];
$regimes    = isset($_GET['regimes'])    && $_GET['regimes']    !== '' ? explode(',', $_GET['regimes'])    : [];
$saveurs    = isset($_GET['saveurs'])    && $_GET['saveurs']    !== '' ? explode(',', $_GET['saveurs'])    : [];

$resultat = [];

foreach ($plats as $plat) {

    if (!empty($categories) && !in_array($plat['categorie'], $categories, true)) {
        continue;
    }

    if (!empty($regimes)) {
        $regimesPlat = $plat['regimes'] ?? [];
        $aTout = true;
        foreach ($regimes as $r) {
            if (!in_array($r, $regimesPlat, true)) {
                $aTout = false;
                break;
            }
        }
        if (!$aTout) continue;
    }

    if (!empty($saveurs) && !in_array($plat['saveur'], $saveurs, true)) {
        continue;
    }

    $resultat[] = $plat;
}

echo json_encode($resultat, JSON_UNESCAPED_UNICODE);
