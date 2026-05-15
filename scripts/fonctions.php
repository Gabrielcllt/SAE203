<?php
function parametres($titre="Titre"){
    echo "<!DOCTYPE html>
    <html lang='fr'>
    <head>
    <meta charset='UTF-8'>
    <title>$titre</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='./style/style.css' rel='stylesheet'>
    </head>
    <body>";
}

function piedpage(){
    echo "<footer class='text-center py-4 fixed-bottom bg-white border-top' style='border-color: var(--border-color) !important;'>
            <small style='color: var(--footer-text); font-weight: 500;'>
                © " . date('Y') . " BreizhHardware - IUT Saint-Malo R&T
            </small>
          </footer>";
}
function navigation(){
    // deconnexion
    if (isset($_POST['action_deconnexion'])) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            }
        $_SESSION = array(); // On vide les variables
        
        session_destroy(); // On détruit la session
        
        // Sécurité anti-bug : Si du HTML a déjà été écrit, on redirige en JS, sinon en PHP
        if (!headers_sent()) {
            header("Location: index.php");
        } else {
            echo "<script>window.location.href='index.php';</script>";
        }
        exit(); 
    }
    $page = basename($_SERVER['PHP_SELF']);

    echo "<nav class='navbar navbar-expand-lg fixed-top navbar-custom'>
    <div class='container'>
        <a class='navbar-brand d-flex align-items-center' href='index0.php'>
            <img src='./images/logo_allongé.png' alt='Logo Breizh Hardware' style='height: 45px; width: auto;' class='d-inline-block align-top'>
        </a>
        
        <button class='navbar-toggler border-0 text-white' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav' style='filter: invert(1);'>
            <span class='navbar-toggler-icon'></span>
        </button>
        
        <div class='collapse navbar-collapse' id='navbarNav'>
            <ul class='navbar-nav ms-auto align-items-center'>";
            
    echo        "<li class='nav-item'><a class='nav-link " . ($page == 'index0.php' ? 'active' : '') . "' href='index0.php'>Accueil</a></li>";
    echo        "<li class='nav-item'><a class='nav-link " . ($page == 'commandes.php' ? 'active' : '') . "' href='commandes.php'>Commandes en cours</a></li>";
    echo        "<li class='nav-item'><a class='nav-link " . ($page == 'annuaire.php' ? 'active' : '') . "' href='annuaire.php'>Annuaire</a></li>";
    
    if (isset($_SESSION['user_groups']) && in_array('admin', $_SESSION['user_groups'])) { 
        echo    "<li class='nav-item'><a class='nav-link " . ($page == 'administration.php' ? 'active' : '') . "' href='administration.php'>Administration</a></li>";
    }
    
    // Le bouton de déconnexion à droite de la liste
    echo "      <li class='nav-item ms-lg-4 mt-2 mt-lg-0'>
                    <form action='' method='POST' class='m-0 d-inline'>
                        <button type='submit' name='action_deconnexion' class='btn btn-logout shadow-sm'>Se déconnecter</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>";
}
?>