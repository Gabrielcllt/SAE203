<?php
// 1. Démarrage de la session et sécurité
session_start();

// Si l'utilisateur n'est pas connecté, redirection immédiate vers la page de connexion
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

// 2. Inclusion de ton fichier de fonctions global
include './scripts/fonctions.php';

// 3. LOGIQUE METIER : Lecture et traitement des données JSON
$fichierJson = './data/utilisateur.json';
$users = [];

if (file_exists($fichierJson)) {
    $usersData = file_get_contents($fichierJson);
    $users = json_decode($usersData, true);
}

// Vérification des droits d'administration du collaborateur connecté
$isAdmin = false;
if (isset($_SESSION['groupes']) && in_array('admin', $_SESSION['groupes'])) {
    $isAdmin = true;
}

$message = "";
$typeMessage = "success";

// Traitement : Suppression d'un membre (Réservé aux administrateurs)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_supprimer']) && $isAdmin) {
    $idSuppr = (int)$_POST['id_membre'];
    $userFound = false;

    foreach ($users as $key => $user) {
        if ($user['id'] === $idSuppr) {
            if ($user['login'] === $_SESSION['login']) {
                $message = "Erreur : Vous ne pouvez pas supprimer votre propre compte !";
                $typeMessage = "danger";
                $userFound = true;
                break;
            }
            unset($users[$key]);
            $userFound = true;
            break;
        }
    }

    if ($userFound && $typeMessage !== "danger") {
        file_put_contents($fichierJson, json_encode(array_values($users), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $message = "Le collaborateur a été retiré de l'annuaire avec succès.";
        $typeMessage = "success";
    }
}

// Traitement : Ajout d'un membre (Réservé aux administrateurs)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_ajouter']) && $isAdmin) {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $fonction = trim($_POST['fonction']);
    $bio = trim($_POST['bio']);
    $photo = trim($_POST['photo']);

    if (!empty($nom) && !empty($prenom) && !empty($login) && !empty($password)) {
        $nextId = empty($users) ? 1 : max(array_column($users, 'id')) + 1;
        $groupesSelectionnes = isset($_POST['groupes']) ? $_POST['groupes'] : ['salariés', 'perso'];

        $newMember = [
            "id" => $nextId,
            "login" => $login,
            "password" => password_hash($password, PASSWORD_DEFAULT), // Mots de passe sécurisés requis en R&T !
            "nom" => strtoupper($nom),
            "prenom" => ucfirst($prenom),
            "fonction" => !empty($fonction) ? $fonction : "Salarié",
            "photo" => !empty($photo) ? $photo : "admin.jpg",
            "bio" => !empty($bio) ? $bio : "...",
            "groupes" => $groupesSelectionnes
        ];

        $users[] = $newMember;
        file_put_contents($fichierJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $message = "Nouveau collaborateur ajouté avec succès !";
        $typeMessage = "success";
    } else {
        $message = "Erreur : Veuillez remplir les champs obligatoires (Nom, Prénom, Identifiant, MDP).";
        $typeMessage = "danger";
    }
}

// Traitement : Moteur de recherche et filtres de l'annuaire
$recherche = isset($_GET['search']) ? trim($_GET['search']) : '';
$membresFiltres = $users;

if (!empty($recherche)) {
    $membresFiltres = array_filter($users, function($u) use ($recherche) {
        return (stripos($u['nom'], $recherche) !== false) || 
               (stripos($u['prenom'], $recherche) !== false) || 
               (stripos($u['fonction'], $recherche) !== false);
    });
}

// 4. AFFICHAGE DES EN-TÊTES DE LA PAGE
parametres("Annuaire");
navigation();
?>

<style>
    .card-member {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
    }
    .card-member:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .avatar-annuaire {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }
</style>

<div class="container mb-5" style="margin-top: 100px;">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="fw-bold text-dark m-0">Annuaire des Collaborateurs</h1>
            <p class="text-muted m-0">Liste du personnel de l'entreprise Breizh Hardware</p>
        </div>
        
        <?php if ($isAdmin): ?>
            <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAjout">
                + Ajouter un collaborateur
            </button>
        <?php endif; ?>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $typeMessage ?> alert-dismissible fade show shadow-sm" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm p-3 mb-4 border-0">
        <form method="GET" action="" class="row g-2">
            <div class="col-md-9 col-sm-8">
                <input type="text" name="search" class="form-control" placeholder="Rechercher un collègue par son nom, prénom ou fonction..." value="<?= htmlspecialchars($recherche) ?>">
            </div>
            <div class="col-md-3 col-sm-4 d-grid">
                <button type="submit" class="btn btn-secondary">Filtrer l'annuaire</button>
            </div>
        </form>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if (!empty($membresFiltres)): ?>
            <?php foreach ($membresFiltres as $membre): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm card-member">
                        <?php 
                            $photoFile = !empty($membre['photo']) ? $membre['photo'] : 'admin.jpg';
                            $cheminPhoto = "./images/" . $photoFile;
                            if (!file_exists($cheminPhoto)) {
                                $cheminPhoto = "./images/admin.jpg";
                            }
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

                        <?php if ($isAdmin): ?>
                            <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3 d-flex justify-content-end">
                                <form method="POST" action="" onsubmit="return confirm('Êtes-vous certain de vouloir retirer ce collaborateur ?');">
                                    <input type="hidden" name="id_membre" value="<?= $membre['id'] ?>">
                                    <button type="submit" name="action_supprimer" class="btn btn-outline-danger btn-sm">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">Aucun collaborateur trouvé pour cette recherche.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($isAdmin): ?>
<div class="modal fade" id="modalAjout" tabindex="-1" aria-labelledby="modalAjoutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalAjoutLabel">Ajouter un nouveau collaborateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Nom *</label>
                        <input type="text" name="nom" class="form-control" required placeholder="Ex: DUPONT">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Prénom *</label>
                        <input type="text" name="prenom" class="form-control" required placeholder="Ex: Jean">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Identifiant unique (Login) *</label>
                        <input type="text" name="login" class="form-control" required placeholder="Ex: jdupont">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Mot de passe *</label>
                        <input type="password" name="password" class="form-control" required placeholder="Ex: mdp123">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Fonction occupée</label>
                        <input type="text" name="fonction" class="form-control" placeholder="Ex: Responsable Infrastructure">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Fichier photo (avec extension)</label>
                        <input type="text" name="photo" class="form-control" placeholder="Ex: jean.jpg">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Groupes d'accès</label>
                        <select name="groupes[]" class="form-select" multiple style="height: 58px;">
                            <option value="salariés" selected>salariés</option>
                            <option value="perso" selected>perso</option>
                            <option value="managers">managers</option>
                            <option value="direction">direction</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Biographie (Courte description)</label>
                        <textarea name="bio" class="form-control" rows="3" placeholder="Description de son parcours ou de son rôle..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="action_ajouter" class="btn btn-primary">Créer le profil</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
// 7. AFFICHAGE DU PIED DE PAGE
piedpage();
?>