<?php
session_start();
require '../functions.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 3) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = dbConnect();
    $habitat_id = $_POST['habitat_id'];
    $comment = $_POST['comment'];
    $vet_id = $_SESSION['user']['id'];

    $stmt = $conn->prepare("INSERT INTO habitat_comments (habitat_id, vet_id, comment, approved) VALUES (?, ?, ?, 0)");
    $stmt->execute([$habitat_id, $vet_id, $comment]);

    header('Location: habitats.php');
    exit;
} else {
    header('Location: ../login.php');
    exit;
}