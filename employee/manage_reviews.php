<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 2) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

// Récupérer tous les avis
$reviews = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les commentaires des vétérinaires
$vetComments = $conn->query("SELECT * FROM habitat_comments ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si une action de suppression ou d'approbation a été demandée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete']) && isset($_POST['review_id'])) {
        $review_id = $_POST['review_id'];
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$review_id]);
    } elseif (isset($_POST['approve']) && isset($_POST['review_id'])) {
        $review_id = $_POST['review_id'];
        $stmt = $conn->prepare("UPDATE reviews SET approved = TRUE WHERE id = ?");
        $stmt->execute([$review_id]);
    } elseif (isset($_POST['delete_comment']) && isset($_POST['comment_id'])) {
        $comment_id = $_POST['comment_id'];
        $stmt = $conn->prepare("DELETE FROM habitat_comments WHERE id = ?");
        $stmt->execute([$comment_id]);
    } elseif (isset($_POST['approve_comment']) && isset($_POST['comment_id'])) {
        $comment_id = $_POST['comment_id'];
        $stmt = $conn->prepare("UPDATE habitat_comments SET approved = TRUE WHERE id = ?");
        $stmt->execute([$comment_id]);
    }
    header('Location: manage_reviews.php'); // Recharger la page pour voir les changements
    exit;
}

include '../templates/header.php';
include 'navbar_employee.php';
?>

<div class="container">
    <h1 class="my-4">Gérer les Avis et Commentaires des Vétérinaires</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Pseudo</th>
                    <th>Objet</th>
                    <th>Avis</th>
                    <th>Approuvé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['visitor_name']); ?></td>
                        <td><?php echo htmlspecialchars($review['subject']); ?></td>
                        <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                        <td><?php echo $review['approved'] ? 'Oui' : 'Non'; ?></td>
                        <td>
                            <?php if (!$review['approved']): ?>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                    <button type="submit" name="approve" class="btn btn-success">Approuver</button>
                                </form>
                            <?php endif; ?>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger">Supprimer</button>
                            </form>
                            <a href="edit_review.php?id=<?php echo $review['id']; ?>" class="btn btn-primary">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h2 class="my-4">Commentaires des Vétérinaires</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID Habitat</th>
                    <th>Nom du Vétérinaire</th>
                    <th>Commentaire</th>
                    <th>Approuvé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vetComments as $comment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($comment['habitat_id']); ?></td>
                        <td><?php echo htmlspecialchars($comment['vet_id']); ?></td>
                        <td><?php echo htmlspecialchars($comment['comment']); ?></td>
                        <td><?php echo $comment['approved'] ? 'Oui' : 'Non'; ?></td>
                        <td>
                            <?php if (!$comment['approved']): ?>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                    <button type="submit" name="approve_comment" class="btn btn-success">Approuver</button>
                                </form>
                            <?php endif; ?>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                <button type="submit" name="delete_comment" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php';