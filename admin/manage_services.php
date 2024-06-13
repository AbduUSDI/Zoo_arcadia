<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

$services = getAll('services');

include '../templates/header.php';
include 'navbar_admin.php';
?>

<div class="container">
    <div class="table-responsive">
    <h1 class="my-4">Gérer les services</h1>
    <a href="add_service.php" class="btn btn-success mb-4">Ajouter un service</a>
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo htmlspecialchars($service['name']); ?></td>
                    <td><?php echo htmlspecialchars($service['description']); ?></td>
                    <td>
                        <?php if (!empty($service['image'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($service['image']); ?>" alt="Image du Service" style="width: 100px;">
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_service.php?id=<?php echo $service['id']; ?>" class="btn btn-warning">Modifier</a>
                        <a href="delete_service.php?id=<?php echo $service['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
