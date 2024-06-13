<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

// Récupération des horaires existants
$query = $conn->query("SELECT * FROM zoo_hours ORDER BY id ASC");
$hours = $query->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['hours'] as $id => $times) {
        $stmt = $conn->prepare("UPDATE zoo_hours SET open_time = ?, close_time = ? WHERE id = ?");
        $stmt->execute([$times['open'], $times['close'], $id]);
    }
    header("Location: zoo_hours.php");
    exit;
}

include '../templates/header.php';
include 'navbar_admin.php';
?>

<div class="container">
    <h2>Modifier les horaires d'ouverture du Zoo</h2>
    <form method="POST">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Jour</th>
                        <th>Heures d'ouverture</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hours as $hour): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hour['day']); ?></td>
                        <td>
                            <input type="time" name="hours[<?php echo $hour['id']; ?>][open]" value="<?php echo substr($hour['open_time'], 0, 5); ?>">
                            -
                            <input type="time" name="hours[<?php echo $hour['id']; ?>][close]" value="<?php echo substr($hour['close_time'], 0, 5); ?>">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour les horaires</button>
    </form>
</div>

<?php include '../templates/footer.php';