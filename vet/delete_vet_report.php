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

// Formulaire pour récupérer le rapport vétérinaire, le sélectionner dans la BDD et le supprimer par son id

if (!isset($_GET['id'])) {
    header('Location: manage_animal_reports.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: manage_animal_reports.php');
    exit;
}

// Méthode préparée pour supprimer le rapport vétérinaire de la base de données, redirection sur la page Gérer rapports après soumission du formulaire DELETE

$stmt = $conn->prepare("DELETE FROM vet_reports WHERE id = ?");
$stmt->execute([$id]);

header('Location: manage_animal_reports.php');
exit;
