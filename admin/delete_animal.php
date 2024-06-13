<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM animals WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header('Location: manage_animals.php');
exit;