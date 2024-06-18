<?php
session_start();
require 'functions.php';
require 'MongoDB.php';

// Connexion à la base données
$db = (new Database())->connect();
$mongoClient = new MongoDB("mongodb://localhost:27017", "zoo_arcadia_click_counts");

if ($db) {
    $habitatId = $_GET['id'];

    // Instance Habitat pour utiliser les méthodes préparées en rapport avec les habitat
    $habitatModel = new Habitat($db);

    // Récupération des habitat par id grâce à la méthode "getParId"
    $habitat = $habitatModel->getParId($habitatId);

    // Récupération des animaux par id d'habitat grâce à la méthode "getAnimauxParHabitat"
    $animals = $habitatModel->getAnimauxParHabitat($habitatId);

    // Récupération de commentaires habitat approuvés grâce à la méthode "getCommentsApprouvés"
    $vetComments = $habitatModel->getCommentsApprouvés($habitatId);
} else {
    // Gestion des erreurs de connexion
    die('Connexion à la base donnée échouée.');
}

include 'templates/header.php';
include 'templates/navbar_visitor.php';
?>

<style>
h1, h2 {
    text-align: center;
}

body {
    padding-top: 48px;
}
</style>

<div class="container">
    <h1 class="my-4"><?php echo htmlspecialchars($habitat['name']); ?></h1>
    <img src="uploads/<?php echo htmlspecialchars($habitat['image']); ?>" class="img-fluid mb-4" alt="<?php echo htmlspecialchars($habitat['name']); ?>">
    <p><?php echo htmlspecialchars($habitat['description']); ?></p>

    <!-- Section pour afficher les commentaires approuvés -->
    <h2>Commentaires sur l'habitat</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Nom du vétérinaire</th>
                    <th>Date du commentaire</th>
                    <th>Commentaire</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vetComments as $comment): ?>
                    <tr>
                        <td scope="col" class="col-3"><?php echo htmlspecialchars($comment['username']); ?></td>
                        <td scope="col" class="col-3"><?php echo htmlspecialchars($comment['created_at']); ?></td>
                        <td scope="col" class="col-6"><?php echo htmlspecialchars($comment['comment']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($vetComments)): ?>
                    <tr>
                        <td colspan="3">Aucun commentaire disponible pour cet habitat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Section pour afficher les animaux -->
    <h2>Animaux</h2>
    <div class="row">
        <?php foreach ($animals as $animal): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img class="card-img-top" src="uploads/<?php echo htmlspecialchars($animal['image']); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($animal['name']); ?></h5>
                        <button onclick="registerClick(<?php echo $animal['id']; ?>)" class="btn btn-success">Voir les détails</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function registerClick(animalId) {
    fetch('record_click.php?animal_id=' + animalId)
        .then(response => response.text())
        .then(data => {
            console.log(data);
            // Redirection vers la page des détails de l'animal
            window.location.href = 'animal.php?id=' + animalId;
        });
}
</script>

<?php include 'templates/footer.php'; ?>
