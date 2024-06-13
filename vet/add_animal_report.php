<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 3) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal_id = $_POST['animal_id'];
    $vet_id = $_SESSION['user']['id']; // Assurez-vous que l'ID du vétérinaire est inclus
    $health_status = $_POST['health_status'];
    $food_given = $_POST['food_given'];
    $food_quantity = $_POST['food_quantity'];
    $visit_date = $_POST['visit_date'];
    $details = $_POST['details'];

    $stmt = $conn->prepare("INSERT INTO vet_reports (animal_id, vet_id, health_status, food_given, food_quantity, visit_date, details) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$animal_id, $vet_id, $health_status, $food_given, $food_quantity, $visit_date, $details]);

    header('Location: manage_animal_reports.php');
    exit;
}

$animals = $conn->query("SELECT * FROM animals")->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
include 'navbar_vet.php';
?>

<div class="container">
    <h1 class="my-4">Ajouter un Rapport Animal</h1>
    <form action="add_animal_report.php" method="POST">
        <div class="form-group">
            <label for="animal_id">Animal</label>
            <select class="form-control" id="animal_id" name="animal_id" required>
                <?php foreach ($animals as $animal): ?>
                    <option value="<?php echo $animal['id']; ?>"><?php echo htmlspecialchars($animal['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="health_status">État</label>
            <input type="text" class="form-control" id="health_status" name="health_status" required>
        </div>
        <div class="form-group">
            <label for="food_given">Nourriture</label>
            <input type="text" class="form-control" id="food_given" name="food_given" required>
        </div>
        <div class="form-group">
            <label for="food_quantity">Grammage</label>
            <input type="number" class="form-control" id="food_quantity" name="food_quantity" required>
        </div>
        <div class="form-group">
            <label for="visit_date">Date de Passage</label>
            <input type="date" class="form-control" id="visit_date" name="visit_date" required>
        </div>
        <div class="form-group">
            <label for="details">Détails (facultatif)</label>
            <textarea class="form-control" id="details" name="details" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<?php include '../templates/footer.php';
