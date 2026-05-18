<?php
session_start();


include './scripts/fonctions.php';
parametres("Annuaire");
navigation();

$fichierJson = './data/utilisateur.json';
echo "<pre>". print_r($fichierJson)."</pre>";

piedpage();
?>