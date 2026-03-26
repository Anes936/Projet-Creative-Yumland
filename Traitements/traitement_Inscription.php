<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Inscription.php");
    exit;
}

$nom       = trim($_POST['nom']);
$prenom    = trim($_POST['prenom']);
$date      = trim($_POST['date']);
$mail      = trim($_POST['mail']);
$telephone = trim($_POST['telephone']);
$ville     = trim($_POST['ville']);
$postal    = trim($_POST['postal']);
$rue       = trim($_POST['rue']);
$nrue      = trim($_POST['nrue']);
$identifiant  = trim($_POST['identifiant']);
$emdp      = $_POST['emdp'];
$cmdp      = $_POST['cmdp'];


$chemin = '../Data/users.json';

if (file_exists($chemin)) {
    $utilisateurs = json_decode(file_get_contents($chemin), true);
} else {
    $utilisateurs = [];
}

$nouvel_utilisateur = [
    'id'       => uniqid(),
    'nom'      => $nom,
    'prenom'   => $prenom,
    'date'     => $date,
    'mail'     => $mail,
    'tel'      => $telephone,
    'adresse'  => [
        'numero' => $nrue,
        'rue'    => $rue,
        'ville'  => $ville,
        'postal' => $postal,
    ],
    'identifiant' => $identifiant,
    'mdp'      => password_hash($emdp, PASSWORD_DEFAULT),
    'role'     => 'client',
    'points'   => 0,
];


$utilisateurs[] = $nouvel_utilisateur;


if (!is_dir('../Data')) {
    mkdir('../Data', 0755, true);
}
file_put_contents(
    $chemin,
    json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);


$_SESSION['connecte'] = true;
$_SESSION['nom']      = $nom;
$_SESSION['prenom']   = $prenom;
$_SESSION['mail']     = $mail;
$_SESSION['identifiant'] = $identifiant;
$_SESSION['role']     = 'client';


header("Location: ../Profil.php");
exit;
?>