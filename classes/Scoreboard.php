<?php
class Scoreboard {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function saveScore($playerName, $score) {
        $stmt = $this->pdo->prepare("INSERT INTO scores (player_name, score) VALUES (?, ?)");
        $stmt->execute([$playerName, $score]);
    }

    public function getTop10() {
        $stmt = $this->pdo->query("SELECT player_name, score FROM scores ORDER BY score ASC LIMIT 10");
        return $stmt->fetchAll();
    }
}
