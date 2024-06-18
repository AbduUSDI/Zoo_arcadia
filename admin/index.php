<?php
// Vérification de l'identification de l'utilisateur, il doit être role 1 donc admin, sinon page login.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../MongoDB.php';
require '../functions.php';

// Connexion à la base de données
$db = new Database();
$conn = $db->connect();

// Connexion à MongoDB , établissement de l'URL et du nom de la database MongoDB
$mongoClient = new MongoDB("mongodb://localhost:27017", "zoo_arcadia_click_counts");

// Instance Animal pour toutes les méthodes préparées en rapport avec les animaux de la BDD MySQL
$animalMySQL = new Animal($conn);

// Instance Habitat pour toutes les méthodes préparées en rapport avec les habitats
$habitat = new Habitat($conn);

// Méthode préparée "getAll" pour récupérer tous les animaux existants
$animals = $animalMySQL->getAll();

// Définition des totaux à 0

$totalLikes = 0;
$totalClicks = 0;

// Calcul du total des likes
foreach ($animals as $animal) {
    $totalLikes += $animal['likes'];
}

// Méthode pour afficher les habitats dans le filtre
$habitats = $habitat->getToutHabitats();

// Méthode pour afficher les animaux par habitat au moment de la sélection de l'habitat
if (isset($_POST['habitat_id'])) {
    $animals = $habitat->getAnimauxParHabitat($_POST['habitat_id']);
}

// Fonction pour trier dans l'ordre décroissant du plus grand au plus petit nombre de like

usort($animals, function($a, $b) {
    return $b['likes'] - $a['likes'];
});

include '../templates/header.php';
include 'navbar_admin.php';
?>

<!-- Conteneur pour afficher la Dashboard (avec la méthode POST) -->

<div class="container">
    <h1 class="my-4">Dashboard Admin</h1>

    <form method="POST" action="">
        <div class="form-group">
            <label for="habitat_id">Filtrer par habitat :</label>
            <select class="form-control" id="habitat_id" name="habitat_id">
                <?php foreach ($habitats as $habitat): ?>
                    <option value="<?php echo $habitat['id']; ?>"><?php echo htmlspecialchars($habitat['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success" style="margin-bottom: 10px;">Filtrer</button>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Animal</th>
                    <th>Likes</th>
                    <th>Clics</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animals as $animal): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($animal['name']); ?></td>
                        <td><?php echo $animal['likes']; ?></td>
                        <td>
                            <?php

                            // Utilisation de la méthode préparée "getClicks" afin d'afficher les nombres de clics récoltés par animal existants dans le zoo

                            $clicks = $mongoClient->getClicks($animal['id']);
                            echo $clicks;
                            $totalClicks += $clicks;
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>

                    <!-- Affichage des totaux pour une vue d'ensemble -->    

                    <td><strong>Total</strong></td>
                    <td><strong><?php echo $totalLikes; ?></strong></td>
                    <td><strong><?php echo $totalClicks; ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
