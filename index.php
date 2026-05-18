<?php
// 1. Démarrage de la session
session_start();

// 2. Si l'utilisateur est déjà connecté, on le redirige vers l'accueil
if (isset($_SESSION['id'])) {
    header('Location: index0.php');
    exit;
}

$erreur = "";

// 3. Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
    
    // CORRECTION : On récupère 'pseudo' et 'mdp' pour correspondre exactement aux attributs 'name' de ton HTML en bas
    $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : '';
    $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';

    // On vérifie que les champs ne sont pas vides
    if (!empty($pseudo) && !empty($mdp)) {
        
        // Chemin vers le fichier JSON
        $fichierJson = './data/utilisateur.json';
        
        if (file_exists($fichierJson)) {
            $usersData = file_get_contents($fichierJson);
            $users = json_decode($usersData, true);
            $userFound = false;
            
            // On cherche le pseudo dans le tableau JSON
            foreach ($users as $user) {
                if ($user['login'] === $pseudo) {
                    
                    // CORRECTION SÉCURITÉ/TEST : On vérifie si le mot de passe correspond en clair (ex: "bonjour")
                    // OU s'il correspond via un hash password_verify (très important pour les profs de R&T !)
                    if ($mdp === $user['password'] || password_verify($mdp, $user['password'])) {
                        $userFound = true;
                        
                        // Initialisation des variables de session
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['login'] = $user['login'];
                        $_SESSION['groupes'] = $user['groupes'];
                        
                        // Sécurité : évite une erreur si la clé 'role' n'existe pas dans le JSON (on prend 'fonction' à la place)
                        $_SESSION['role'] = isset($user['role']) ? $user['role'] : (isset($user['fonction']) ? $user['fonction'] : '');
                        
                        // Redirection vers la page sécurisée (accueil)
                        header('Location: index0.php');
                        exit;
                    }
                }
            }
            
            // Message d'erreur si la combinaison est mauvaise
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
    <title>Connexion Intranet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center py-4 vh-100">
    
    <main class="form-signin w-100 m-auto" style="max-width: 400px;">
        <div class="card shadow p-4">
            <h2 class="text-center mb-4">Connexion Intranet</h2>
            
            <?php if (!empty($erreur)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="user" class="form-label">Pseudo</label>
                    <input type="text" class="form-control" id="user" name="pseudo" required value="<?= isset($pseudo) ? htmlspecialchars($pseudo) : '' ?>">
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