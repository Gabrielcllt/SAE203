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
// Mettre nombre de commandes ici

piedpage();
?>