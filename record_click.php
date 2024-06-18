<?php
require 'vendor/autoload.php';
require 'MongoDB.php';

$mongoClient = new MongoDB("mongodb://localhost:27017", "zoo_arcadia_click_counts");

$animal_id = isset($_GET['animal_id']) ? (int)$_GET['animal_id'] : 0;

if ($animal_id) {
    $mongoClient->recordClick($animal_id);
    echo "Clic enregistré !";
} else {
    echo "Aucun animal spécifié.";
}