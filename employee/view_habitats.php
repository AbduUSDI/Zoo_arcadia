<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 2) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();
$stmt = $conn->query("SELECT * FROM habitats");
$habitats = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
?>
<h1>Habitats</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Image</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($habitats as $habitat): ?>
        <tr>
            <td><?php echo htmlspecialchars($habitat['id']); ?></td>
            <td><?php echo htmlspecialchars($habitat['name']); ?></td>
            <td><?php echo htmlspecialchars($habitat['description']); ?></td>
            <td><img src="<?php echo htmlspecialchars($habitat['image']); ?>" alt="Image de l'habitat" width="100"></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../templates/footer.php'; ?>
