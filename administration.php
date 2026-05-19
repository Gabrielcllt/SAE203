<?php
session_start();

// Si l'utilisateur n'est pas connecté, redirection immédiate vers la page de connexion
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

include './scripts/fonctions.php';

// On pointe bien sur le fichier des clients
$fichierJson = './data/client.json';
$clients = [];

if (file_exists($fichierJson)) {
    $clientsData = file_get_contents($fichierJson);
    $clients = json_decode($clientsData, true);
}

parametres("Annuaire Clients");
navigation();
?>

<div class="container mb-5" style="margin-top: 100px;">
    
    <div class="mb-5">
        <h1 class="fw-bold text-dark m-0">Annuaire des Clients</h1>
        <p class="text-muted m-0">Base de données clients de l'entreprise Breizh Hardware</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if (!empty($clients) && is_array($clients)): ?>
            <?php foreach ($clients as $client): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-start border-4 <?php echo ($client['type'] === 'Entreprise') ? 'border-primary' : 'border-success'; ?>">
                        
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge <?php echo ($client['type'] === 'Entreprise') ? 'bg-primary' : 'bg-success'; ?> fw-normal">
                                    <?= htmlspecialchars($client['type']) ?>
                                </span>
                                <small class="text-muted">ID: #<?= htmlspecialchars($client['id']) ?></small>
                            </div>

                            <h5 class="card-title fw-bold mb-3 text-dark">
                                <?= htmlspecialchars($client['nom']) ?> <?= htmlspecialchars($client['prenom'] ?? '') ?>
                            </h5>
                            
                            <div class="card-text small text-muted flex-grow-1">
                                <p class="mb-1"><strong>Tél :</strong> <?= htmlspecialchars($client['telephone']) ?></p>
                                <p class="mb-1"><strong>Mail :</strong> <?= htmlspecialchars($client['email']) ?></p>
                                <p class="mb-0 text-truncate"><strong>Adresse :</strong><br>
                                    <?= htmlspecialchars($client['adresse']) ?><br>
                                    <?= htmlspecialchars($client['code_postal']) ?> <?= htmlspecialchars($client['ville']) ?>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">Aucun client enregistré dans la base de données pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
piedpage();
?>