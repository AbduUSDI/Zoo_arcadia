<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image'];

    if ($image['error'] == UPLOAD_ERR_OK) {
        $imageName = time() . '_' . $image['name'];
        move_uploaded_file($image['tmp_name'], '../uploads/' . $imageName);
    } else {
        $imageName = null;
    }

    $stmt = $conn->prepare("INSERT INTO habitats (name, description, image) VALUES (?, ?, ?)");
    $stmt->execute([$name, $description, $imageName]);

    header('Location: manage_habitats.php');
    exit;
}

include '../templates/header.php';
include 'navbar_admin.php';
?>

<div class="container">
    <h1 class="my-4">Ajouter un Habitat</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control-file" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
