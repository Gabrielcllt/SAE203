<?php
session_start();

// 1. SÉCURITÉ
if (!isset($_SESSION['groupes']) || !in_array('admin', $_SESSION['groupes'])) {
    header('Location: index0.php');
    exit;
}

include './scripts/fonctions.php';
$fichierJson = './data/utilisateur.json';
$users = file_exists($fichierJson) ? json_decode(file_get_contents($fichierJson), true) : [];

// 2. TRAITEMENT : AJOUT
if (isset($_POST['action_ajout'])) {
    $users[] = [
        "id" => time(), // Génère un ID unique basé sur le temps actuel
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

// 3. TRAITEMENT : SUPPRESSION
if (isset($_POST['action_suppression'])) {
    $users = array_values(array_filter($users, function($u) {
        return $u['id'] != $_POST['id_utilisateur'];
    }));
    file_put_contents($fichierJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: administration.php');
    exit;
}

parametres("Administration");
navigation();
?>

<div class="container mb-5" style="margin-top: 100px;">
    
    <h1 class="fw-bold text-dark mb-4">Espace Administration</h1>

    <div class="card p-4 mb-5 shadow-sm border-custom">
        <form action="" method="POST" class="row g-3">
            <div class="col-md-3"><input type="text" name="prenom" placeholder="Prénom" class="form-control" required></div>
            <div class="col-md-3"><input type="text" name="nom" placeholder="Nom" class="form-control" required></div>
            <div class="col-md-3"><input type="text" name="login" placeholder="Identifiant" class="form-control" required></div>
            <div class="col-md-3"><input type="password" name="password" placeholder="Mot de passe" class="form-control" required></div>
            <div class="col-md-4"><input type="text" name="fonction" placeholder="Fonction dans l'entreprise" class="form-control" required></div>
            <div class="col-md-4"><input type="text" name="photo" placeholder="Nom photo (Ex: jean.jpg)" class="form-control"></div>
            <div class="col-md-4 d-flex align-items-center">
                <label class="form-check-label text-danger fw-bold">
                    <input type="checkbox" name="is_admin" value="1" class="form-check-input me-2"> Accorder droits Admin
                </label>
            </div>
            <div class="col-12"><textarea name="bio" placeholder="Biographie / Description" class="form-control" rows="2" required></textarea></div>
            <div class="col-12 text-end"><button type="submit" name="action_ajout" class="btn btn-deconnexion">Ajouter le collaborateur</button></div>
        </form>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php foreach ($users as $membre): ?>
            <div class="col">
                <div class="card h-100 shadow-sm card-member d-flex flex-column">
                    <?php 
                        $photoFile = !empty($membre['photo']) ? $membre['photo'] : 'admin.jpg';
                        $cheminPhoto = "./images/" . $photoFile;
                    ?>
                    <img src="<?= htmlspecialchars($cheminPhoto) ?>" class="avatar-annuaire" alt="Photo de <?= htmlspecialchars($membre['prenom']) ?>">
                
                    <div class="card-body d-flex flex-column flex-grow-1">
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

                    <div class="p-3 pt-0">
                        <form action="" method="POST" onsubmit="return confirm('Supprimer ce profil ?');">
                            <input type="hidden" name="id_utilisateur" value="<?= $membre['id'] ?>">
                            <button type="submit" name="action_suppression" class="btn btn-sm btn-outline-danger w-100 fw-bold" style="border-radius: 20px;">
                                Supprimer le profil
                            </button>
                        </form>
                    </div>
                </div> </div>
        <?php endforeach; ?>
    </div>
</div>

<?php piedpage(); ?>