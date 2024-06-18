<?php
require 'vendor/autoload.php';

use MongoDB\Client;

class MongoDB {
    private $mongoClient;
    private $mongoCollection;

    public function __construct($url, $databaseName) {
        $this->mongoClient = new Client($url);
        $db = $this->mongoClient->selectDatabase($databaseName);
        $this->mongoCollection = $db->clicks;
    }

    public function recordClick($animal_id) {
        $this->mongoCollection->updateOne(
            ['animal_id' => $animal_id],
            ['$inc' => ['clicks' => 1]],
            ['upsert' => true]
        );
    }

    public function getClicks($animal_id) {
        $click = $this->mongoCollection->findOne(['animal_id' => $animal_id]);
        return $click ? $click['clicks'] : 0;
    }
}