<?php

// Vérification de l'identification de l'utiliateur, il doit être role 1 donc admin, sinon page login.php

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';

// Connexion à la base de données

$db = new Database();
$conn = $db->connect();

// Instance Animal pour utiliser les méthodes préparées en rapport avec les animaux

$animal = new Animal($conn);

// Création de deux définitions pour les filtres

$selectedDate = $_GET['visit_date'] ?? null;
$selectedAnimalId = $_GET['animal_id'] ?? null;

// Utilisation de la méthode préparée "filtreDateAnimal" pour faire fonctionner la fonction afin de filtrer les rapports en fonction de la sélection

$reports = $animal->filtresDateAnimal($selectedDate, $selectedAnimalId);

include '../templates/header.php';
include 'navbar_admin.php';
?>

<!-- Conteneur pour filtrer les rapports vétérinaires -->

<div class="container">
    <h1 class="my-4">Gérer les Rapports Vétérinaires</h1>
    <form action="manage_animal_reports.php" method="GET">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="filterDate">Date de Visite:</label>
                <select class="form-control" id="filterDate" name="visit_date">
                    <option value="">Toutes les dates</option>
                    <?php
                    $dates = $conn->query("SELECT DISTINCT visit_date FROM vet_reports ORDER BY visit_date DESC")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($dates as $date) {
                        $selected = ($date['visit_date'] == $selectedDate) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($date['visit_date']) . '" ' . $selected . '>' . htmlspecialchars($date['visit_date']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="filterAnimal">Animal:</label>
                <select class="form-control" id="filterAnimal" name="animal_id">
                    <option value="">Tous les animaux</option>
                    <?php
                    $animals = $conn->query("SELECT id, name FROM animals ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($animals as $animal) {
                        $selected = ($animal['id'] == $selectedAnimalId) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($animal['id']) . '" ' . $selected . '>' . htmlspecialchars($animal['name']) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Filtrer</button>
    </form>
    <br>

<!-- Tableau pour afficher les rapports vétérinaires -->

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Animal</th>
                    <th>État de Santé</th>
                    <th>Nourriture Donnée</th>
                    <th>Grammage</th>
                    <th>Date de Passage</th>
                    <th>Détails</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['animal_name']); ?></td>
                        <td><?php echo htmlspecialchars($report['health_status']); ?></td>
                        <td><?php echo htmlspecialchars($report['food_given']); ?></td>
                        <td><?php echo htmlspecialchars($report['food_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($report['visit_date']); ?></td>
                        <td><?php echo htmlspecialchars($report['details']); ?></td>
                        <td>
                            <a href="delete_vet_report.php?id=<?php echo $report['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php';
