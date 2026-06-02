<?php
session_start();

// SÉCURITÉ
if (!isset($_SESSION['groupes']) || !in_array('admin', $_SESSION['groupes'])) {
    header('Location: index0.php');
    exit;
}

include './scripts/fonctions.php';
$fichierJson = './data/utilisateur.json';

// Si le JSON est malformé, on force un tableau vide pour éviter le crash
$users = file_exists($fichierJson) ? json_decode(file_get_contents($fichierJson), true) : [];
if (!is_array($users)) { $users = []; }

// AJOUT
if (isset($_POST['action_ajout'])) {
    $users[] = [
        "id" => time(), 
        "login" => trim($_POST['login']),
        "password" => trim($_POST['password']),
        "nom" => trim($_POST['nom']),
        "prenom" => trim($_POST['prenom']),
        "fonction" => trim($_POST['fonction']),
        "photo" => !empty($_POST['photo']) ? trim($_POST['photo']) : 'admin.jpg',
        "bio" => trim($_POST['bio']),
        "groupes" => isset($_POST['is_admin']) ? ["admin", "salariés", "perso"] : ["salariés", "perso"]
    ];
    file_put_contents($fichierJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: administration.php');
    exit;
}

// 3. TRAITEMENT : MODIFICATION
if (isset($_POST['action_modification'])) {
    foreach ($users as &$u) {
        if ($u['id'] == $_POST['id_utilisateur']) {
            $u['login'] = trim($_POST['login']);
            $u['password'] = trim($_POST['password']);
            $u['nom'] = trim($_POST['nom']);
            $u['prenom'] = trim($_POST['prenom']);
            $u['fonction'] = trim($_POST['fonction']);
            $u['photo'] = !empty($_POST['photo']) ? trim($_POST['photo']) : 'admin.jpg';
            $u['bio'] = trim($_POST['bio']);
            $u['groupes'] = isset($_POST['is_admin']) ? ["admin", "salariés", "perso"] : ["salariés", "perso"];
        }
    }
    file_put_contents($fichierJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: administration.php');
    exit;
}

// 4. TRAITEMENT : SUPPRESSION
if (isset($_POST['action_suppression'])) {
    $users = array_values(array_filter($users, function($u) {
        return $u['id'] != $_POST['id_utilisateur'];
    }));
    file_put_contents($fichierJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: administration.php');
    exit;
}

// 5. DETECTION DU MODE EDITION
$uEdit = null;
if (isset($_GET['edit'])) {
    foreach ($users as $u) {
        if ($u['id'] == $_GET['edit']) { $uEdit = $u; break; }
    }
}

parametres("Administration");
navigation();
?>

<div class="container mb-5" style="margin-top: 100px;">
    
    <h1 class="fw-bold text-dark mb-4"><?= $uEdit ? "Modifier un profil" : "Espace Administration" ?></h1>

    <div class="card p-4 mb-5 shadow-sm border-custom">
        <form action="" method="POST" class="row g-3">
            <?php if ($uEdit): ?>
                <input type="hidden" name="id_utilisateur" value="<?= $uEdit['id'] ?>">
            <?php endif; ?>

            <div class="col-md-3"><input type="text" name="prenom" placeholder="Prénom" class="form-control" value="<?= $uEdit ? htmlspecialchars($uEdit['prenom']) : '' ?>" required></div>
            <div class="col-md-3"><input type="text" name="nom" placeholder="Nom" class="form-control" value="<?= $uEdit ? htmlspecialchars($uEdit['nom']) : '' ?>" required></div>
            <div class="col-md-3"><input type="text" name="login" placeholder="Identifiant" class="form-control" value="<?= $uEdit ? htmlspecialchars($uEdit['login']) : '' ?>" required></div>
            <div class="col-md-3"><input type="password" name="password" placeholder="Mot de passe" class="form-control" value="<?= $uEdit ? htmlspecialchars($uEdit['password']) : '' ?>" required></div>
            <div class="col-md-4"><input type="text" name="fonction" placeholder="Fonction" class="form-control" value="<?= $uEdit ? htmlspecialchars($uEdit['fonction']) : '' ?>" required></div>
            <div class="col-md-4"><input type="text" name="photo" placeholder="Nom photo (Ex: jean.jpg)" class="form-control" value="<?= $uEdit ? htmlspecialchars($uEdit['photo']) : '' ?>"></div>
            <div class="col-md-4 d-flex align-items-center">
                <label class="form-check-label text-danger fw-bold">
                    <input type="checkbox" name="is_admin" value="1" class="form-check-input me-2" <?= ($uEdit && in_array('admin', $uEdit['groupes'])) ? 'checked' : '' ?>> Droits Admin
                </label>
            </div>
            <div class="col-12"><textarea name="bio" placeholder="Biographie" class="form-control" rows="2" required><?= $uEdit ? htmlspecialchars($uEdit['bio']) : '' ?></textarea></div>
            <div class="col-12 text-end">
                <?php if ($uEdit): ?>
                    <a href="administration.php" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" name="action_modification" class="btn btn-primary">Enregistrer les modifications</button>
                <?php else: ?>
                    <button type="submit" name="action_ajout" class="btn btn-deconnexion">Ajouter le collaborateur</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php foreach ($users as $membre): ?>
            <div class="col">
                <div class="card h-100 shadow-sm card-member d-flex flex-column">
                    <img src="./images/<?= !empty($membre['photo']) ? htmlspecialchars($membre['photo']) : 'admin.jpg' ?>" class="avatar-annuaire" alt="Photo">
                
                    <div class="card-body d-flex flex-column flex-grow-1">
                        <h5 class="card-title fw-bold mb-1 text-truncate">
                            <?= htmlspecialchars($membre['nom']) ?> <?= htmlspecialchars($membre['prenom']) ?>
                        </h5>
                        <div class="mb-2">
                            <?php if (in_array('admin', $membre['groupes'])): ?><span class="badge bg-danger me-1">Admin</span><?php endif; ?>
                            <span class="badge bg-secondary"><?= htmlspecialchars($membre['fonction']) ?></span>
                        </div>
                        <p class="card-text text-muted small flex-grow-1">
                            <?= nl2br(htmlspecialchars($membre['bio'])) ?>
                        </p>
                    </div>

                    <div class="p-3 pt-0 d-flex gap-2">
                        <a href="administration.php?edit=<?= $membre['id'] ?>" class="btn btn-sm btn-outline-primary w-50 fw-bold" style="border-radius: 20px;">
                            Modifier
                        </a>
                        <form action="" method="POST" onsubmit="return confirm('Supprimer ce profil ?');" class="w-50 m-0">
                            <input type="hidden" name="id_utilisateur" value="<?= $membre['id'] ?>">
                            <button type="submit" name="action_suppression" class="btn btn-sm btn-outline-danger w-100 fw-bold" style="border-radius: 20px;">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php piedpage(); ?>