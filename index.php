<?php
// 1. Démarrage de la session (comme conseillé dans la vidéo)
session_start();

// 2. Si l'utilisateur est déjà connecté, on le redirige vers l'accueil pour l'empêcher de se reconnecter
if (isset($_SESSION['id'])) {
    header('Location: index0.php');
    exit;
}

$erreur = "";

// 3. Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
    $pseudo = trim($_POST['pseudo']);
    $mdp = $_POST['mdp'];

    // On vérifie que les champs ne sont pas vides
    if (!empty($pseudo) && !empty($mdp)) {
        
        // Au lieu de SQL, on lit le fichier JSON
        $usersData = file_get_contents('./data/utilisateur.json');
        $users = json_decode($usersData, true);
        $userFound = false;
        
        // On cherche le pseudo dans le tableau JSON
        foreach ($users as $user) {
            if ($user['pseudo'] === $pseudo) {
                // Utilisation de password_verify() pour comparer le mot de passe tapé avec celui crypté (comme dans la vidéo)
                if (password_verify($mdp, $user['password'])) {
                    $userFound = true;
                    
                    // Initialisation des variables de session
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['pseudo'] = $user['pseudo'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['groupes'] = $user['groupes'];
                    
                    // Redirection vers la page sécurisée (accueil)
                    header('Location: index0.php');
                    exit;
                }
            }
        }
        
        // Message d'erreur vague par sécurité (conseil de la vidéo)
        if (!$userFound) {
            $erreur = "La combinaison pseudo / mot de passe est incorrecte.";
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
    <title>Connexion Intranet</title>
    <!-- Chargement de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center py-4 vh-100">
    
    <main class="form-signin w-100 m-auto" style="max-width: 400px;">
        <div class="card shadow p-4">
            <h2 class="text-center mb-4">Connexion Intranet</h2>
            
            <!-- Affichage du message d'erreur -->
            <?php if (!empty($erreur)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="pseudo" class="form-label">Pseudo</label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" required>
                </div>
                
                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="mdp" name="mdp" required>
                </div>
                
                <button class="btn btn-primary w-100" type="submit" name="connexion">Se connecter</button>
            </form>
        </div>
    </main>

</body>
</html>