<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 2) {
    // Rediriger si l'utilisateur n'est pas un employé
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal_id = $_POST['animal_id'];
    $food_given = $_POST['food_given'];
    $food_quantity = $_POST['food_quantity'];
    $date_given = $_POST['date_given'];

    $stmt = $conn->prepare("INSERT INTO food (animal_id, food_given, food_quantity, date_given) VALUES (?, ?, ?, ?)");
    $stmt->execute([$animal_id, $food_given, $food_quantity, $date_given]);

    header('Location: manage_food.php');
    exit;
}