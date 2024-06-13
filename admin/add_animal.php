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
    $species = $_POST['species'];
    $habitat_id = $_POST['habitat_id'];
    $image = $_FILES['image'];

    // Vérifiez d'abord si un fichier a été téléchargé
    if ($image['name']) {
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($image["name"]);
        move_uploaded_file($image["tmp_name"], $targetFile);
    } else {
        // Si aucun fichier n'a été téléchargé, définissez $image sur une valeur par défaut ou NULL, selon votre logique d'application
        $image = 'chemin_vers_image_par_defaut.jpg'; // Définissez le chemin vers une image par défaut si nécessaire
    }

    $stmt = $conn->prepare("INSERT INTO animals (name, species, habitat_id, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $species, $habitat_id, $targetFile]); // Utilisez $targetFile comme valeur pour l'image

    header('Location: manage_animals.php');
    exit;
}

$habitats = $conn->query("SELECT * FROM habitats")->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
include 'navbar_admin.php';
?>

<div class="container">
    <h1 class="my-4">Ajouter un Animal</h1>
    <form action="add_animal.php" method="POST" enctype="multipart/form-data"> <!-- Ajoutez enctype="multipart/form-data" -->
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="species">Espèce</label>
            <input type="text" class="form-control" id="species" name="species" required>
        </div>
        <div class="form-group">
            <label for="habitat_id">Habitat</label>
            <select class="form-control" id="habitat_id" name="habitat_id" required>
                <?php foreach ($habitats as $habitat): ?>
                    <option value="<?php echo $habitat['id']; ?>"><?php echo htmlspecialchars($habitat['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image" required> <!-- Remplacez le champ de saisie par un champ de téléchargement de fichier -->
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
