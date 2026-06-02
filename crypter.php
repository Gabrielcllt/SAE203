<?php
$fichierJson = './data/utilisateur.json';
if (file_exists($fichierJson)) {
    $usersData = file_get_contents($fichierJson);
    $users = json_decode($usersData, true);
    
    foreach ($users as &$user) {
        if (strpos($user['password'], '$2y$') !== 0) {
            // Hachage du mot de passe
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        }
    }
    
    file_put_contents($fichierJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "<h1>BINGO !</h1><p>Les mots de passe ont ete haches avec succes. Va verifier ton fichier utilisateur.json dans VS Code.</p>";
} else {
    echo "Erreur : fichier introuvable.";
}
?>