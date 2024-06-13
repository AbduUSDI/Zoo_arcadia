<?php
require '../functions.php';
$conn = dbConnect();

// Vérifier si l'ID de l'avis est fourni
if (!isset($_GET['id'])) {
    echo "Aucun avis spécifié.";
    exit;
}

$reviewId = $_GET['id'];

// Récupérer les données de l'avis
$stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->execute([$reviewId]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$review) {
    echo "Avis non trouvé.";
    exit;
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'] ?? $review['visitor_name'];
    $subject = $_POST['subject'] ?? $review['subject'];
    $review_text = $_POST['review_text'] ?? $review['review_text'];

    $updateStmt = $conn->prepare("UPDATE reviews SET visitor_name = ?, subject = ?, review_text = ? WHERE id = ?");
    $updateStmt->execute([$pseudo, $subject, $review_text, $reviewId]);

    header('Location: manage_reviews.php'); // Rediriger vers la page de gestion des avis
    exit;
}

include '../templates/header.php';
include 'navbar_employee.php';
?>

<div class="container">
    <h1>Modifier l'avis</h1>
    <form action="edit_review.php?id=<?php echo htmlspecialchars($reviewId); ?>" method="POST">
        <div class="mb-3">
            <label for="pseudo" class="form-label">Pseudo:</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo htmlspecialchars($review['visitor_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="subject" class="form-label">Objet:</label>
            <input type="text" class="form-control" id="subject" name="subject" value="<?php echo htmlspecialchars($review['subject']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="review_text" class="form-label">Texte de l'avis:</label>
            <textarea class="form-control" id="review_text" name="review_text" rows="3" required><?php echo htmlspecialchars($review['review_text']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>

<?php include '../templates/footer.php';

