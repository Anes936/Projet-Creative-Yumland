<?php
// Calcule le total du panier en appliquant automatiquement les box (menus) dès que c'est possible.
function calculerTotalPanier(array $panier, array $platsParId, array $menus): array
{
    $platsRestants = $panier;
    $boxesAppliquees = [];

    // Pour chaque box, on regarde combien de fois on peut la composer avec les plats du panier.
    foreach ($menus as $menu) {
        if (empty($menu['plats'])) {
            continue;
        }
        $nbBoxes = PHP_INT_MAX;
        $valide = true;
        foreach ($menu['plats'] as $platId) {
            if (!isset($platsRestants[$platId]) || $platsRestants[$platId] <= 0) {
                $valide = false;
                break;
            }
            $nbBoxes = min($nbBoxes, $platsRestants[$platId]);
        }
        if (!$valide || $nbBoxes <= 0) {
            continue;
        }
        foreach ($menu['plats'] as $platId) {
            $platsRestants[$platId] -= $nbBoxes;
        }
        $prixInitial = 0;
        foreach ($menu['plats'] as $platId) {
            if (isset($platsParId[$platId])) {
                $prixInitial += $platsParId[$platId]['prix'];
            }
        }
        $boxesAppliquees[] = [
            'id'           => $menu['id'],
            'nom'          => $menu['nom'],
            'nb'           => $nbBoxes,
            'prix_box'     => $menu['prix'],
            'prix_initial' => $prixInitial,
            'economie'     => ($prixInitial - $menu['prix']) * $nbBoxes,
        ];
    }

    $total = 0;
    foreach ($platsRestants as $id => $qte) {
        if ($qte > 0 && isset($platsParId[$id])) {
            $total += $platsParId[$id]['prix'] * $qte;
        }
    }
    foreach ($boxesAppliquees as $box) {
        $total += $box['prix_box'] * $box['nb'];
    }

    $economieTotale = 0;
    foreach ($boxesAppliquees as $box) {
        $economieTotale += $box['economie'];
    }

    return [
        'total'     => $total,
        'boxes'     => $boxesAppliquees,
        'restants'  => $platsRestants,
        'economie'  => $economieTotale,
    ];
}
