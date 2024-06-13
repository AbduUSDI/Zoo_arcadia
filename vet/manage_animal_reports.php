<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 3) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();
$reports = $conn->query("SELECT ar.*, a.name AS animal_name FROM vet_reports ar JOIN animals a ON ar.animal_id = a.id ORDER BY ar.visit_date DESC")->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
include 'navbar_vet.php';
?>

<div class="container">
    <h1 class="my-4">Gérer les Rapports Animaux</h1>
    <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Animal</th>
                <th>Date de Passage</th>
                <th>État</th>
                <th>Nourriture</th>
                <th>Grammage</th>
                <th>Détails</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?php echo htmlspecialchars($report['animal_name']); ?></td>
                    <td><?php echo htmlspecialchars($report['visit_date']); ?></td>
                    <td><?php echo htmlspecialchars($report['health_status']); ?></td>
                    <td><?php echo htmlspecialchars($report['food_given']); ?></td>
                    <td><?php echo htmlspecialchars($report['food_quantity']); ?></td>
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
