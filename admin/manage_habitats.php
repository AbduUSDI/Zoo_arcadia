<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();
$habitats = $conn->query("SELECT * FROM habitats")->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
include 'navbar_admin.php';
?>

<div class="container">
    <h1 class="my-4">Gestion des Habitats</h1>
    <div class="table-responsive">
    <a href="add_habitat.php" class="btn btn-success mb-4">Ajouter un Habitat</a>
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($habitats as $habitat): ?>
                <tr>
                    <td><?php echo htmlspecialchars($habitat['id']); ?></td>
                    <td><?php echo htmlspecialchars($habitat['name']); ?></td>
                    <td><?php echo strip_tags($habitat['description']); ?></td> <!-- Utiliser strip_tags pour supprimer les balises HTML -->
                    <td><img src="../uploads/<?php echo htmlspecialchars($habitat['image']); ?>" alt="<?php echo htmlspecialchars($habitat['name']); ?>" width="100"></td>
                    <td>
                        <a href="edit_habitat.php?id=<?php echo $habitat['id']; ?>" class="btn btn-warning">Modifier</a>
                        <a href="delete_habitat.php?id=<?php echo $habitat['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet habitat ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>

<?php include '../templates/footer.php'; ?>
