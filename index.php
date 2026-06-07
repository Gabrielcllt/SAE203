<?php
// 1. Démarrage de la session
session_start();

// 2. Si l'utilisateur est déjà connecté, on le redirige vers l'accueil
if (isset($_SESSION['id'])) {
    header('Location: index0.php');
    exit;
}

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
    
    $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : '';
    $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';

    if (!empty($pseudo) && !empty($mdp)) {
        
        $fichierJson = './data/utilisateur.json';
        
        if (file_exists($fichierJson)) {
            $usersData = file_get_contents($fichierJson);
            $users = json_decode($usersData, true);
            $userFound = false;
            
            foreach ($users as $user) {
                if ($user['login'] === $pseudo) {
                    
                    // Vérification compatible avec tes nouveaux mots de passe hachés
                    if ($mdp === $user['password'] || password_verify($mdp, $user['password'])) {
                        $userFound = true;
                        
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['login'] = $user['login'];
                        $_SESSION['groupes'] = $user['groupes'];
                        
                        $_SESSION['role'] = isset($user['role']) ? $user['role'] : (isset($user['fonction']) ? $user['fonction'] : '');
                        
                        header('Location: index0.php');
                        exit;
                    }
                }
            }
            
            if (!$userFound) {
                $erreur = "La combinaison login / mot de passe est incorrecte.";
            }
        } else {
            $erreur = "Erreur système : Le fichier des utilisateurs est introuvable.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Intranet - Breizh Hardware</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #081121; /* Bleu nuit WordPress */
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background-color: #0f1c35; /* Carte légèrement plus claire */
            border: 1px solid #1e3050;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        .form-control {
            background-color: #162442; /* Champs sombres */
            border: 1px solid #2a3f66;
            color: #ffffff;
        }
        .form-control:focus {
            background-color: #1c2b4d;
            color: #ffffff;
            border-color: #7b2cbf; /* Liseré violet au clic */
            box-shadow: 0 0 0 0.25rem rgba(123, 44, 191, 0.25);
        }
        .form-label {
            color: #a0aec0;
            font-weight: 500;
        }
        .btn-custom {
            /* Dégradé violet vibrant assorti au bouton de ta vitrine */
            background: linear-gradient(90deg, #6200ea 0%, #8e24aa 100%);
            border: none;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-custom:hover {
            background: linear-gradient(90deg, #7c4dff 0%, #ab47bc 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(123, 44, 191, 0.4);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 25px;
        }
        .logo-container img {
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body class="d-flex align-items-center py-4 vh-100">
    
    <main class="form-signin w-100 m-auto" style="max-width: 420px;">
        
        <div class="logo-container">
            <img src="./images/logo_allongé.png" alt="Logo Breizh Hardware">
        </div>

        <div class="card login-card p-4">
            <h3 class="text-center mb-4 fw-bold text-white">Portail Intranet</h3>
            
            <?php if (!empty($erreur)): ?>
                <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-white text-center" role="alert">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="user" class="form-label">Identifiant</label>
                    <input type="text" class="form-control form-control-lg" id="user" name="pseudo" required value="<?= isset($pseudo) ? htmlspecialchars($pseudo) : '' ?>">
                </div>
                
                <div class="mb-4">
                    <label for="mdp" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control form-control-lg" id="mdp" name="mdp" required>
                </div>
                
                <button class="btn btn-custom w-100 rounded-pill btn-lg" type="submit" name="connexion">Se connecter</button>
            </form>
        </div>
        
        <p class="mt-4 mb-3 text-center text-white small">&copy; 2026 Breizh Hardware - IUT R&T</p>
    </main>

</body>
</html>