<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 2) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();
$animals = $conn->query("SELECT * FROM animals")->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
include 'navbar_employee.php';
?>

<div class="container">
    <h1 class="my-4">Gérer la Nourriture des Animaux</h1>
    <form action="add_food_record.php" method="POST">
        <div class="form-group">
            <label for="animal_id">Animal</label>
            <select class="form-control" id="animal_id" name="animal_id" required>
                <?php foreach ($animals as $animal): ?>
                    <option value="<?php echo $animal['id']; ?>"><?php echo htmlspecialchars($animal['name']); ?></option>
                <?php endforeach; ?>
            </select>
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
            <label for="date_given">Date</label>
            <input type="date" class="form-control" id="date_given" name="date_given" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
