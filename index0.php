<?php
session_start();

$fichierJsonUsers = './data/utilisateur.json';
if (file_exists($fichierJsonUsers)) {
    $usersData = file_get_contents($fichierJsonUsers);
    $users = json_decode($usersData, true);
    $modifie = false;

    foreach ($users as &$user) {
        // Si le mot de passe n'est pas encore haché (ne commence pas par $2y$)
        if (isset($user['password']) && strpos($user['password'], '$2y$') !== 0) {
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
            $modifie = true;
        }
    }

    // Si on a modifié au moins un mot de passe, on sauvegarde le fichier
    if ($modifie) {
        file_put_contents($fichierJsonUsers, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "<script>alert('Succès : Les mots de passe ont été hachés dans utilisateur.json ! Tu peux effacer le bloc de code.');</script>";
    }
}

if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

include './scripts/fonctions.php';

$nb_employes = 0;
if (file_exists('./data/utilisateur.json')) {
    $employesData = json_decode(file_get_contents('./data/utilisateur.json'), true);
    $nb_employes = is_array($employesData) ? count($employesData) : 0;
}

$nb_clients = 0;
if (file_exists('./data/client.json')) {
    $clientsData = json_decode(file_get_contents('./data/client.json'), true);
    $nb_clients = is_array($clientsData) ? count($clientsData) : 0;
}

$nb_partenaires = 0;
if (file_exists('./data/partenaires.json')) {
    $commandes = json_decode(file_get_contents('./data/commandes.json'), true);
    $nb_commandes = is_array($commandes) ? count($commandes) : 0;
}

parametres("Accueil Intranet");
navigation();
?>

<div class="container mb-5">
    
   
    <div class="mb-5 p-4 rounded-3 border shadow-sm" style="background-color: var(--c1); border-color: var(--c2) !important;">
        <h1 class="fw-bold mb-2" style="color: var(--c10);">Degemer mat, <?= htmlspecialchars($_SESSION['prenom'] ?? 'Collaborateur') ?> ! 👋</h1>
        <p class="fs-5 m-0" style="color: var(--c6);">Bienvenue sur l'intranet de <strong>Breizh Hardware</strong>.</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        
        <div class="col">
            <div class="card h-100 shadow-sm border-0 bg-white" style="border-bottom: 4px solid var(--c6) !important;">
                <div class="card-body text-center p-4">
                    <h4 class="fw-bold" style="color: var(--c10);">L'Équipe</h4>
                    <p class="text-muted">Annuaire interne</p>
                    <h2 class="fw-bold mb-4" style="color: var(--c6);"><?= $nb_employes ?> <span class="fs-6 text-muted fw-normal">membres</span></h2>
                    <a href="employes.php" class="btn w-100" style="background-color: var(--c10); color: white;">Gérer le personnel</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 shadow-sm border-0 bg-white" style="border-bottom: 4px solid var(--c3) !important;">
                <div class="card-body text-center p-4">
                    <h4 class="fw-bold" style="color: var(--c10);">Nos Clients</h4>
                    <p class="text-muted">Base de données</p>
                    <h2 class="fw-bold mb-4" style="color: var(--c6);"><?= $nb_clients ?> <span class="fs-6 text-muted fw-normal">clients</span></h2>
                    <a href="clients.php" class="btn w-100" style="background-color: var(--c6); color: white;">Consulter les fiches</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 shadow-sm border-0 bg-white" style="border-bottom: 4px solid var(--c9) !important;">
                <div class="card-body text-center p-4">
                    <h4 class="fw-bold" style="color: var(--c10);">Commandes</h4>
                    <p class="text-muted">Nombre de commandes</p>
                    <h2 class="fw-bold mb-4" style="color: var(--c6);"><?= $nb_partenaires ?> <span class="fs-6 text-muted fw-normal">actives</span></h2>
                    <a href="partenaires.php" class="btn w-100" style="background-color: var(--c10); color: white;">Gérer les commandes</a>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
piedpage();
?>