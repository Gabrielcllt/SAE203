<?php
session_start();
// Si l'utilisateur n'est pas connecté, redirection immédiate vers la page de connexion
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

include './scripts/fonctions.php';
parametres("Accueil");
navigation();
// nombre de commandes
$Json = file_get_contents('./data/commandes.json');
$commande = json_decode($Json, true);
$commande = count($commande);
echo 'Voici le nombre de commande : '.$commande;

piedpage();
?>