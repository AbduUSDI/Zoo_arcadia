<?php
session_start();
require '../functions.php';
$conn = dbConnect();

if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../index.php');
    exit;
}

$animalId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $species = $_POST['species'];
    $habitat_id = $_POST['habitat_id'];
    $image = $_FILES['image'];

    if ($image['name']) {
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($image["name"]);
        move_uploaded_file($image["tmp_name"], $targetFile);

        $stmt = $conn->prepare("UPDATE animals SET name = ?, species = ?, habitat_id = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $species, $habitat_id, $targetFile, $animalId]);
    } else {
        $stmt = $conn->prepare("UPDATE animals SET name = ?, species = ?, habitat_id = ? WHERE id = ?");
        $stmt->execute([$name, $species, $habitat_id, $animalId]);
    }

    header('Location: manage_animals.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM animals WHERE id = ?");
$stmt->execute([$animalId]);
$animal = $stmt->fetch();

$stmt = $conn->prepare("SELECT * FROM habitats");
$stmt->execute();
$habitats = $stmt->fetchAll();

include '../templates/header.php';
include 'navbar_admin.php';
?>

<div class="container">
    <h1 class="my-4">Modifier un Animal</h1>
    <form action="edit_animal.php?id=<?php echo $animal['id']; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($animal['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="species">Espèce</label>
            <input type="text" class="form-control" id="species" name="species" value="<?php echo htmlspecialchars($animal['species']); ?>" required>
        </div>
        <div class="form-group">
            <label for="habitat_id">Habitat</label>
            <select class="form-control" id="habitat_id" name="habitat_id" required>
                <?php foreach ($habitats as $habitat): ?>
                    <option value="<?php echo $habitat['id']; ?>" <?php if ($habitat['id'] == $animal['habitat_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($habitat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image (laisser vide pour ne pas changer)</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
