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
            echo "<img src='./images/logo.png' width='200' height='50'/> ";
            if (isset($_SESSION['pseudo'])){
                echo "<br> Bienvenue, ".$_SESSION['pseudo']."<br>";
                echo "<a href='deconnexion.php'>Déconnexion</a>";

            }else {
                echo "<br><a href='connexion.php'>Connexion</a>";
            }
            echo "</header>";
        };
        function piedpage(){
            echo "<footer class='mt-4 p-5 rounded  text-center bg-dark text-white'> &copy". date('Y') ."<br>  L'adresse IP et le port utilisé sont ". 
            $_SERVER['REMOTE_ADDR'] .":". $_SERVER['REMOTE_PORT'] . "<br> <div><a href='#'>LinkedIn</a>" ." | ". "<a href='#'>Instagram</a></div> </footer>";
            
        };
        function piedpageaccueil(){
            echo "<footer class='mt-4 p-5 rounded fixed-bottom text-center bg-dark text-white'> &copy". date('Y') ."<br>  L'adresse IP et le port utilisé sont ". 
            $_SERVER['REMOTE_ADDR'] .":". $_SERVER['REMOTE_PORT'] . "<br> <div><a href='#'>LinkedIn</a>" ." | ". "<a href='#'>Instagram</a></div> </footer>";
            
        };
        function navigation(){
            $page = basename($_SERVER['PHP_SELF']);

            echo "<nav class='navbar navbar-expand-lg fixed-top bg-dark'>
            <div class='container'>
                <a class='navbar-brand fw-bold text-white' href='accueil.php'>
                    <span>COUCOUVOIT</span>
                </a>
                
                <button class='navbar-toggler border-0' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav'>
                    <span class='navbar-toggler-icon'></span>
                </button>
                <div class='collapse navbar-collapse' id='navbarNav'>
                    <ul class='navbar-nav ms-auto '>";
            echo        "<li class='nav-item'". ($page =='accueil.php' ? 'active' : '')."><a class='nav-link text-white' href='accueil.php'>Accueil</a></li>";
            echo        "<li class='nav-item'". ($page =='visualiser.php' ? 'active' : '')."><a class='nav-link text-white' href='visualiser.php'>Annonces</a></li>";
            echo        "<li class='nav-item'". ($page =='administration.php' ? 'active' : '')."><a class='nav-link text-white' href='administration.php'>Administration</a></li>";
            echo        "<li class='nav-item'". ($page =='profil.php' ? 'active' : '')."><a class='nav-link text-white' href='profil.php'>Profil</a></li>";
            echo        "<li class='nav-item'". ($page =='wiki.php' ? 'active' : '')."><a class='nav-link text-white' href='wiki.php'>Wiki</a></li>
                    </ul>
                </div>
            </div>
        </nav>";
        };
?>

