<?php
session_start();

// Vérification de la connexion
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

include './scripts/fonctions.php';

// Lecture simplifiée du fichier JSON
$fichierJson = './data/commandes.json';
$commandes = [];
if (file_exists($fichierJson)) {
    $commandes = json_decode(file_get_contents($fichierJson), true);
}

parametres("Suivi des Commandes");
navigation();
?>

<div class="container my-5">
    
    <div class="mb-4">
        <h1 class="fw-bold">Suivi des Commandes</h1>
        <p class="text-muted">Gestion des PC en cours de reconditionnement</p>
    </div>

    <div class="table-responsive shadow-sm rounded border">
        <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Réf. Commande</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Produit</th>
                    <th>Statut</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($commandes) && is_array($commandes)): ?>
                    <?php foreach (array_reverse($commandes) as $cmd): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($cmd['id']) ?></td>
                            <td class="text-muted"><?= htmlspecialchars($cmd['date'] ?? 'N/A') ?></td>
                            <td>
                                <strong><?= htmlspecialchars($cmd['client']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($cmd['email']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($cmd['produit']) ?></td>
                            <td>
                                <span class="badge bg-secondary"><?= htmlspecialchars($cmd['statut'] ?? 'À traiter') ?></span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary">Gérer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Aucune commande en cours pour le moment.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
piedpage();
?>