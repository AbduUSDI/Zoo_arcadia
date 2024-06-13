<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 2) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();
$stmt = $conn->query("SELECT animals.*, habitats.name AS habitat_name, animals.image FROM animals LEFT JOIN habitats ON animals.habitat_id = habitats.id");
$animals = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
include 'navbar_employee.php';
?>
<div class="container">
    <h1 class="my-4">Animaux</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Espèce</th>
                    <th>Habitat</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animals as $animal): ?>
                <tr>
                    <td><?php echo htmlspecialchars($animal['id']); ?></td>
                    <td><?php echo htmlspecialchars($animal['name']); ?></td>
                    <td><?php echo htmlspecialchars($animal['species']); ?></td>
                    <td><?php echo htmlspecialchars($animal['habitat_name']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($animal['image']); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>" style="width: 100px;"></td>
                    <td>
                        <!-- Accordéon pour les commentaires -->
                        <div class="accordion" id="accordionExample-<?php echo $animal['id']; ?>">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne-<?php echo $animal['id']; ?>">
                                    <button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#collapseExample-<?php echo $animal['id']; ?>" aria-expanded="false" aria-controls="collapseExample">
                                        Voir les commentaires
                                    </button>
                                </h2>
                                <div id="collapseExample-<?php echo $animal['id']; ?>" class="collapse" <?php echo $animal['id']; ?>" <?php echo $animal['id']; ?>">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <?php
                                            $comments = getReviewsForAnimal($animal['id']);
                                            foreach ($comments as $comment): ?>
                                                <li class="list-group-item">
                                                    <strong><?php echo htmlspecialchars($comment['visitor_name']); ?>:</strong> <?php echo htmlspecialchars($comment['review_text']); ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php';
