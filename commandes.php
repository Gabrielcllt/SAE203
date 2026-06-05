<?php
include './scripts/fonctions.php';
secure_session_start();

if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

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
        <h1 class="fw-bold" style="color: var(--c10);">Suivi des Commandes</h1>
    </div>

    <table class="table align-middle">
        <thead style="color: var(--c6); border-bottom: 2px solid var(--c3);">
            <tr>
                <th>Commande</th>
                <th>Client</th>
                <th>Produit</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($commandes) && is_array($commandes)): ?>
                <?php foreach (array_reverse($commandes) as $cmd): ?>
                    <tr>
                        <td class="fw-bold" style="color: var(--c9);"><?= htmlspecialchars($cmd['id']) ?></td>
                        <td style="color: var(--c10);"><?= htmlspecialchars($cmd['client']) ?></td>
                        <td><?= htmlspecialchars($cmd['produit']) ?></td>
                        <td>
                            <span class="badge" style="background-color: var(--c6); font-weight: normal;">
                                <?= htmlspecialchars($cmd['statut'] ?? 'À traiter') ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center py-3 text-muted">Aucune commande.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
piedpage();
?>