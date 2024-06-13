<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

if (!isset($_GET['id'])) {
    header('Location: manage_animal_reports.php');
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM vet_reports WHERE id = ?");
$stmt->execute([$id]);

header('Location: manage_animal_reports.php');
exit;