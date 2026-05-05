<?php
            
        function parametres($titre="Titre"){
            echo "<!DOCTYPE html>
            <html lang='fr'>
            <head>
            <meta charset='UTF-8'>
            <title>$titre</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            </head>";
        };
        function entete(){
            echo "<header class='mt-4 p-5 rounded text-center'>";
            echo "<img src='./images/logo_sae203_final.png'/> ";
            echo "<form action='deconnecter()' method='POST'> <button type='submit'>Se déconnecter</button> </form>"
            if (isset($_POST['action_deconnexion'])) {// si l'on appuie sur le bouton on lance la fonction pour se deconnecter
                deconnecter();
            };
            echo "</header>";
        };
        function piedpage(){
            echo "<footer class=' text-center py-5 fixed-bottom'>
            <small class='text-muted'>© date('Y') BreizhHardware - IUT Saint-Malo R&T</small>
            </footer>";
            
        };
        function navigation(){
            $page = basename($_SERVER['PHP_SELF']);

            echo "<nav class='navbar navbar-expand-lg fixed-top bg-dark'>
            <div class='container'>
                <a class='navbar-brand fw-bold text-white' href='index0.php'>
                    <span>Breizh Hardware</span>
                </a>
                
                <button class='navbar-toggler border-0' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav'>
                    <span class='navbar-toggler-icon'></span>
                </button>
                <div class='collapse navbar-collapse' id='navbarNav'>
                    <ul class='navbar-nav ms-auto '>";
            echo        "<li class='nav-item'". ($page =='index0.php' ? 'active' : '')."><a class='nav-link text-white' href='index0.php'>Accueil</a></li>";
            echo        "<li class='nav-item'". ($page =='commandes.php' ? 'active' : '')."><a class='nav-link text-white' href='commandes.php'>Commandes en cours</a></li>";
            echo        "<li class='nav-item'". ($page =='annuaire.php' ? 'active' : '')."><a class='nav-link text-white' href='annuaire.php'>Annuaire de l'entreprise</a></li>";
            if (isset($_SESSION['user_groups']) && in_array('admin', $_SESSION['user_groups'])) { 
            echo        "<li class='nav-item'". ($page =='administration.php' ? 'active' : '')."><a class='nav-link text-white' href='administration.php'>Administration</a></li>";
            };
            echo "</ul>
                </div>
            </div>
        </nav>";
        };
        function deconnecter(){
        // Vider toutes les variables de session
        $_SESSION = array();
        session_destroy();
        // redirection
        header("Location: index.php");
        exit();  
        };
?>

