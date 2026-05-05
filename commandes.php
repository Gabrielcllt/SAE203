<?php
session_start();


include './fonctions.php';
parametres("Accueil");
navigation();
entete();

// Mettre toutes les commandes avec le détail
piedpage();
?>