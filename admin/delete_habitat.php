<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';
$conn = dbConnect();

$id = $_GET['id'];
$conn->prepare("DELETE FROM habitats WHERE id = ?")->execute([$id]);

header('Location: manage_habitats.php');
exit;