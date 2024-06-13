<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 3) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';

// Connexion à la base de données

$db = new Database();
$conn = $db->connect();

// Instance Animal pour afficher les animaux dans le tableau

$animalManager = new Animal($conn);

include '../templates/header.php';
include 'navbar_vet.php';
?>

<div class="container">
    <h1 class="my-4">Gestion des Animaux</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Prénom</th>
                    <th>Race</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php

                // Utilisation de la méthode getAll pour les animaux

                $animals = $animalManager->getAll();
                foreach ($animals as $animal) {

                    // Utilisation ici de "echo" pour afficher le tableau des animaux ainsi que le bouton pour rediriger vers Ajouter rapport
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($animal['id']) . "</td>";  // Prévention contre XSS grâce à htmlspecialchars
                    echo "<td>" . htmlspecialchars($animal['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($animal['species']) . "</td>";
                    echo "<td><img src=\"../uploads/" . htmlspecialchars($animal['image']) . "\" alt=\"" . htmlspecialchars($animal['name']) . "\" width=\"100\"></td>";

                    // Ajout d'un bouton pour ajouter un rapport sur l'animal en question

                    echo "<td><a href=\"add_animal_report.php?id=" . $animal['id'] . "\" class=\"btn btn-success\">Ajouter un rapport</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
