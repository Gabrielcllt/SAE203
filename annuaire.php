<?php
// 1. Démarrage de la session et sécurité
session_start();

// Si l'utilisateur n'est pas connecté, redirection immédiate vers la page de connexion
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

include './scripts/fonctions.php';

$fichierJson = './data/utilisateur.json';
$users = [];

if (file_exists($fichierJson)) {
    $usersData = file_get_contents($fichierJson);
    $users = json_decode($usersData, true);
}

parametres("Annuaire");
navigation();
?>

<div class="container mb-5" style="margin-top: 100px;">
    
    <div class="mb-5">
        <h1 class="fw-bold text-dark m-0">Annuaire des Collaborateurs</h1>
        <p class="text-muted m-0">Liste du personnel de l'entreprise Breizh Hardware</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if (!empty($users) && is_array($users)): ?>
            <?php foreach ($users as $membre): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm card-member">
                        <?php 
                            $photoFile = !empty($membre['photo']) ? $membre['photo'] : 'admin.jpg';
                            $cheminPhoto = "./images/" . $photoFile;
                        ?>
                        <img src="<?= htmlspecialchars($cheminPhoto) ?>" class="avatar-annuaire" alt="Photo de <?= htmlspecialchars($membre['prenom']) ?>">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-1 text-truncate">
                                <?= htmlspecialchars($membre['nom']) ?> <?= htmlspecialchars($membre['prenom']) ?>
                            </h5>
                            <span class="badge bg-secondary align-self-start mb-3 fw-normal fs-7">
                                <?= htmlspecialchars($membre['fonction']) ?>
                            </span>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= nl2br(htmlspecialchars($membre['bio'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">Aucun collaborateur n'est inscrit dans l'annuaire pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
piedpage();
?>