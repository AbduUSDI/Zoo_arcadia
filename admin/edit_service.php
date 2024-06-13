<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

if (!isset($_GET['id'])) {
    header('Location: manage_services.php');
    exit;
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image'];

    if ($image['size'] > 0) {
        $imageName = basename($image['name']);
        $targetFile = '../uploads/' . $imageName;
        move_uploaded_file($image['tmp_name'], $targetFile);

        $stmt = $conn->prepare("UPDATE services SET name = ?, description = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $description, $imageName, $id]);
    } else {
        $stmt = $conn->prepare("UPDATE services SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $description, $id]);
    }

    header('Location: manage_services.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    header('Location: manage_services.php');
    exit;
}

include '../templates/header.php';
include 'navbar_admin.php';
?>

<div class="container">
    <h1 class="my-4">Modifier le Service</h1>
    <form action="edit_service.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($service['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($service['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image (laisser vide pour ne pas changer)</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
        <?php if (!empty($service['image'])): ?>
            <div class="form-group">
                <img src="../uploads/<?php echo htmlspecialchars($service['image']); ?>" alt="Image actuelle" style="width: 100px;">
            </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
