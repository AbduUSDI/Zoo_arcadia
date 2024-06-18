<?php

// Inclusion des fichier obligatoires pour le fonctionnement

require 'vendor/autoload.php';
require 'MongoDB.php';

// Instance MongoDB pour établir la connexion à la BDD 

$mongoClient = new MongoDB("mongodb://localhost:27017", "zoo_arcadia_click_counts");

// Récupération de la requête de l'URL

$animal_id = isset($_GET['animal_id']) ? (int)$_GET['animal_id'] : 0;

// Si c'est récupéré alors il utilise "recordCLick" sinon "Aucun animal spécifié"

if ($animal_id) {
    $mongoClient->recordClick($animal_id);
    echo "Clic enregistré !";
} else {
    echo "Aucun animal spécifié.";
}
