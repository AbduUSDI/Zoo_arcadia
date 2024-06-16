<?php

// Vérification de l'identification de l'utiliateur, il doit être role 1 donc admin, sinon page login.php

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';

// Connexion à la base de onnées

$db = new Database();
$conn = $db->connect();

// Instance Animal pour utiliser les méthodes préparées en rapport avec les animaux

$animal = new Animal($conn);

// Instance Habitat pour utiliser les méthodes préparées en rapport avec les habitats

$habitat = new Habitat($conn);

// Utilisation de la méthode "getAll" de Animal pour récupérer les informations de tout les animaux

$animals = $animal->getAll();
$totalLikes = 0;

// Calcul du total des likes
foreach ($animals as $animal) {
    $totalLikes += $animal['likes'];
}

// Récupération de tous les habitats disponibles, en utilisant la méthoe "getToutHabitats"

$habitats = $habitat->getToutHabitats();

// Filtrage par habitat si le formulaire est soumis en utilisant la méthode "getAnimauxParHabitat" grâce à la soumission du formulaire POST (menu Select pour filtrer)

if (isset($_POST['habitat_id'])) {
    $animals = $habitat->getAnimauxParHabitat($_POST['habitat_id']);
}

// Tri des animaux par nombre de likes (ordre décroissant)

usort($animals, function($a, $b) {
    return $b['likes'] - $a['likes'];
});

include '../templates/header.php';
include 'navbar_admin.php';
?>

<!-- Conteneur pour afficher la Dashboard -->

<div class="container">
    <h1 class="my-4">Dashboard Admin</h1>

    <!-- Formulaire pour filtrer par habitat -->

    <form method="POST" action="">
        <div class="form-group">
            <label for="habitat_id">Filtrer par habitat :</label>
            <select class="form-control" id="habitat_id" name="habitat_id">
                <?php foreach ($habitats as $habitat): ?>
                    <option value="<?php echo $habitat['id']; ?>"><?php echo htmlspecialchars($habitat['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success" style="margin-bottom: 10px;">Filtrer</button>
    </form>
    
    <!-- Tableau dashboard -->
 
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Animal</th>
                    <th>Likes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animals as $animal): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($animal['name']); ?></td>
                        <td><?php echo $animal['likes']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong><?php echo $totalLikes; ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
