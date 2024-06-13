<?php
session_start();
require '../functions.php';

// Vérifier si l'ID de l'utilisateur à supprimer est présent dans la requête
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: manage_users.php');
    exit();
}

$userId = $_GET['id'];

// Connexion à la base de données
$conn = dbConnect();

try {
    // Supprimer l'utilisateur de la base de données
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    // Rediriger vers la page des utilisateurs avec un message de succès
    $_SESSION['message'] = "Utilisateur supprimé avec succès.";
    header('Location: manage_users.php');
    exit();
} catch (PDOException $e) {
    // Rediriger vers la page des utilisateurs avec un message d'erreur
    $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
    header('Location: manage_users.php');
    exit();
}