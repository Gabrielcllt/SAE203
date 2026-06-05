<?php
session_start();

// Vérification de la connexion
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

include './scripts/fonctions.php';

$fichierJson = './data/commandes.json';
$commandes = [];

if (file_exists($fichierJson)) {
    $commandesData = file_get_contents($fichierJson);
    $commandes = json_decode($commandesData, true);
}

parametres("Suivi des Commandes");
navigation();
?>

<div class="container mb-5">
    
    <div class="mb-5">
        <h1 class="fw-bold m-0" style="color: var(--c10);">Suivi des Commandes</h1>
        <p class="text-muted m-0">Gestion des PC en cours de reconditionnement</p>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid var(--c6) !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead style="background-color: var(--c1); color: var(--c10); border-bottom: 2px solid var(--c3);">
                        <tr>
                            <th class="px-4 py-3 border-0">Réf. Commande</th>
                            <th class="px-4 py-3 border-0">Date</th>
                            <th class="px-4 py-3 border-0">Client</th>
                            <th class="px-4 py-3 border-0">Produit</th>
                            <th class="px-4 py-3 border-0">Statut</th>
                            <th class="px-4 py-3 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($commandes) && is_array($commandes)): ?>
                           
                            <?php foreach (array_reverse($commandes) as $cmd): ?>
                                <tr>
                                
                                    <td class="px-4 py-3 fw-bold" style="color: var(--c9);">
                                        <?= htmlspecialchars($cmd['id']) ?>
                                    </td>
                                    
                            
                                    <td class="px-4 py-3 text-muted small">
                                        <?= htmlspecialchars($cmd['date'] ?? 'N/A') ?>
                                    </td>
                                    
                                 
                                    <td class="px-4 py-3">
                                        <div style="color: var(--c10); font-weight: 500;">
                                            <?= htmlspecialchars($cmd['client']) ?>
                                        </div>
                                        <div class="text-muted small">
                                            <?= htmlspecialchars($cmd['email']) ?>
                                        </div>
                                    </td>
                                    
                                
                                    <td class="px-4 py-3 fw-medium" style="color: var(--c10);">
                                        <?= htmlspecialchars($cmd['produit']) ?>
                                    </td>
                                    
                                  
                                    <td class="px-4 py-3">
                                        <span class="badge" style="background-color: var(--c6); font-weight: normal; padding: 6px 10px;">
                                            <?= htmlspecialchars($cmd['statut'] ?? 'À traiter') ?>
                                        </span>
                                    </td>
                                    
                             
                                    <td class="px-4 py-3 text-end">
                                        <button class="btn btn-sm" style="background-color: var(--c1); color: var(--c6); border: 1px solid var(--c3); font-weight: 600;">
                                            Gérer
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted fs-5">
                                    Aucune commande en cours pour le moment.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
piedpage();
?>