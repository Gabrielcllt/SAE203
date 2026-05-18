<?php
session_start();

// Si l'utilisateur n'est pas connecté, redirection immédiate vers la page de connexion
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}
include './scripts/fonctions.php';
parametres("Annuaire");
navigation();

$fichierJson = './data/utilisateur.json';
echo "<pre>". print_r($fichierJson)."</pre>";

piedpage();
?>