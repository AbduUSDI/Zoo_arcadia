<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

// Gestion des filtres
$selectedDate = $_GET['visit_date'] ?? null;
$selectedAnimalId = $_GET['animal_id'] ?? null;

$query = "SELECT ar.*, a.name as animal_name FROM vet_reports ar JOIN animals a ON ar.animal_id = a.id";
$conditions = [];

if (!empty($selectedDate)) {
    $conditions[] = "ar.visit_date = '$selectedDate'";
}
if (!empty($selectedAnimalId)) {
    $conditions[] = "ar.animal_id = $selectedAnimalId";
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$query .= " ORDER BY ar.visit_date DESC";
$reports = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
include 'navbar_admin.php';
?>

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
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>
    <br>
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