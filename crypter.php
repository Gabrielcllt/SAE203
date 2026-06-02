<?php
$fichierJson = './data/utilisateur.json';
$usersData = file_get_contents($fichierJson);
$users = json_decode($usersData, true);

foreach ($users as &$user) {
    if (isset($user['password']) && strpos($user['password'], '$2y$') !== 0) {
        // Hachage du mot de passe
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
    }
}

// On prépare le nouveau texte JSON propre
$nouveauJson = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

echo "<h1 style='color: #6200ea;'>Plan B : La méthode forte !</h1>";
echo "<p>Puisque Windows bloque l'écriture, voici ton fichier avec les mots de passe sécurisés.</p>";
echo "<p><b>Action requise :</b> Cliquer dans la boîte ci-dessous, fais <code>Ctrl+A</code> (tout sélectionner), puis <code>Ctrl+C</code> (copier). Va dans ton fichier <i>utilisateur.json</i> sur VS Code, efface tout, et fais <code>Ctrl+V</code> (coller).</p>";

// On affiche le résultat dans une boîte de texte facile à copier
echo "<textarea style='width:100%; height:600px; font-family: monospace; background-color: #f8f9fa; border: 2px solid #6200ea; padding: 10px;'>" . htmlspecialchars($nouveauJson) . "</textarea>";
?>