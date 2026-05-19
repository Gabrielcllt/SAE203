<?php
session_start();


if (!isset($_SESSION['id'])) {
    header('Location: index0.php');
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


parametres("Accueil Intranet");
navigation();
?>

<div class="container mb-5" style="margin-top: 100px;">
    
    <div class="mb-5 p-4 bg-light rounded-3 border shadow-sm">
        <h1 class="fw-bold text-dark mb-2">Degemer mat, <?= htmlspecialchars($_SESSION['prenom'] ?? 'Collaborateur') ?> !</h1>
        <p class="text-muted fs-5 m-0">Bienvenue sur l'intranet de <strong>Breizh Hardware</strong>. Que souhaitez-vous gérer aujourd'hui ?</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        
        <div class="col">
            <div class="card h-100 shadow-sm border-0 bg-white">
                <div class="card-body text-center p-4">
                    <h4 class="fw-bold text-dark">L'Équipe</h4>
                    <p class="text-muted">Annuaire interne</p>
                    <h2 class="text-primary fw-bold mb-4"><?= $nb_employes ?> <span class="fs-6 text-muted fw-normal">membres</span></h2>
                    <a href="annuaire.php" class="btn btn-outline-primary w-100">Gérer le personnel</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 shadow-sm border-0 bg-white">
                <div class="card-body text-center p-4">
                    <h4 class="fw-bold text-dark">Nos Clients</h4>
                    <p class="text-muted">Base de données</p>
                    <h2 class="text-success fw-bold mb-4"><?= $nb_clients ?> <span class="fs-6 text-muted fw-normal">clients</span></h2>
                    <a href="annuaire_client.php" class="btn btn-outline-success w-100">Consulter les fiches</a>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
piedpage();
?>