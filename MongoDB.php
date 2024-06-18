<?php
require 'vendor/autoload.php';

use MongoDB\Client;

class MongoDB {
    private $mongoClient;
    private $mongoCollection;
    // Consctructeur pour établir la connexion à la base de donnée au moment de l'utilisation de la classe
    public function __construct($url, $databaseName) {
        $this->mongoClient = new Client($url);
        $db = $this->mongoClient->selectDatabase($databaseName);
        $this->mongoCollection = $db->clicks;
    }
    // Méthode pour récupérer les clics effectués sur le bouton de la carte animal
    public function recordClick($animal_id) {
        $this->mongoCollection->updateOne(
            ['animal_id' => $animal_id],
            ['$inc' => ['clicks' => 1]],
            ['upsert' => true]
        );
    }
    // Méthode pour afficher les clics récoltées sur le tableau dashboard
    public function getClicks($animal_id) {
        $click = $this->mongoCollection->findOne(['animal_id' => $animal_id]);
        return $click ? $click['clicks'] : 0;
    }
}
