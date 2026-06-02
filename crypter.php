<?php
$fichierJson = './data/utilisateur.json';

echo "<h2>Diagnostic de sécurité :</h2>";

if (file_exists($fichierJson)) {
    
    // 1. On vérifie si Windows autorise WAMP à modifier le fichier
    if (!is_writable($fichierJson)) {
        echo "<p style='color:red;'><b>Erreur :</b> Le fichier utilisateur.json est verrouillé en lecture seule. WAMP n'a pas le droit d'écrire dedans.</p>";
        exit;
    }

    $usersData = file_get_contents($fichierJson);
    $users = json_decode($usersData, true);

    // 2. On vérifie si le JSON est valide (pas de virgule oubliée)
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<p style='color:red;'><b>Erreur de syntaxe JSON :</b> PHP ne peut pas lire ton fichier utilisateur.json.</p>";
        echo "<p>Détail de l'erreur : <b>" . json_last_error_msg() . "</b></p>";
        echo "<p>Vérifie bien qu'il ne manque pas une virgule ou une accolade dans ton fichier.</p>";
        exit;
    }

    $compteur = 0;
    foreach ($users as &$user) {
        if (isset($user['password']) && strpos($user['password'], '$2y$') !== 0) {
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
            $compteur++;
        }
    }

    // 3. On sauvegarde et on vérifie que la sauvegarde a fonctionné
    if ($compteur > 0) {
        $result = file_put_contents($fichierJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        if ($result !== false) {
            echo "<h1 style='color:green;'>BINGO !</h1>";
            echo "<p><b>$compteur</b> mots de passe ont été hachés et le fichier a été sauvegardé avec succès.</p>";
            echo "<p><i>Astuce : Dans VS Code, ferme l'onglet utilisateur.json et rouvre-le pour forcer l'affichage de la mise à jour.</i></p>";
        } else {
            echo "<p style='color:red;'><b>Erreur critique :</b> file_put_contents a échoué. Impossible d'écrire dans le fichier.</p>";
        }
    } else {
        echo "<h1 style='color:orange;'>Aucune modification</h1>";
        echo "<p>Tous les mots de passe sont déjà chiffrés ou aucun mot de passe n'a été trouvé.</p>";
    }

} else {
    echo "<p style='color:red;'><b>Erreur :</b> fichier introuvable au chemin $fichierJson.</p>";
}
?>