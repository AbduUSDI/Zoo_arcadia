<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

if (!isset($_GET['id'])) {
    header('Location: manage_habitat.php');
    exit;
}

$habitat_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM habitats WHERE id = ?");
$stmt->execute([$habitat_id]);
$habitat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$habitat) {
    header('Location: manage_habitat.php');
    exit;
}

$animals = $conn->prepare("SELECT * FROM animals WHERE habitat_id = ?");
$animals->execute([$habitat_id]);
$animals = $animals->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
?>

<div class="container">
    <h1 class="my-4">Habitat: <?php echo htmlspecialchars($habitat['name']); ?></h1>
    <p><?php echo htmlspecialchars($habitat['description']); ?></p>
    <?php if ($habitat['image_path']): ?>
        <img src="../<?php echo htmlspecialchars($habitat['image_path']); ?>" alt="Image de l'habitat" class="img-fluid">
    <?php endif; ?>

    <h2 class="my-4">Animaux dans cet Habitat</h2>
    <div class="table-responsive">
        <table class="table table-striped table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Espèce</th>
                    <th>Statut de Santé</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animals as $animal): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($animal['id']); ?></td>
                        <td><?php echo htmlspecialchars($animal['name']); ?></td>
                        <td><?php echo htmlspecialchars($animal['species']); ?></td>
                        <td><?php echo htmlspecialchars($animal['health_status']); ?></td>
                        <td>
                            <?php if ($animal['image_path']): ?>
                                <img src="../<?php echo htmlspecialchars($animal['image_path']); ?>" alt="Image de l'animal" class="img-fluid" style="max-width: 100px;">
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
