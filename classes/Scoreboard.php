<?php
class Scoreboard {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Enregistre un score (et crée le joueur s’il n’existe pas)
    public function saveScore($playerName, $score, $pairsCount = 0) {
        // Vérifier si le joueur existe
        $stmt = $this->pdo->prepare("SELECT id, best_score, games_played FROM players WHERE name = ?");
        $stmt->execute([$playerName]);
        $player = $stmt->fetch();

        if ($player) {
            $playerId = $player['id'];
            $bestScore = $player['best_score'];
            $gamesPlayed = $player['games_played'] + 1;

            // Mettre à jour best_score si le nouveau score est meilleur
            if ($bestScore === null || $score < $bestScore) {
                $bestScore = $score;
            }

            $update = $this->pdo->prepare("UPDATE players SET best_score=?, games_played=? WHERE id=?");
            $update->execute([$bestScore, $gamesPlayed, $playerId]);
        } else {
            // Créer un nouveau joueur
            $insert = $this->pdo->prepare("INSERT INTO players (name, best_score, games_played) VALUES (?, ?, 1)");
            $insert->execute([$playerName, $score]);
            $playerId = $this->pdo->lastInsertId();
        }

        // Enregistrer le score dans la table scores
        $stmt = $this->pdo->prepare("
            INSERT INTO scores (player_id, score, pairs_count)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$playerId, $score, $pairsCount]);
    }

    // Récupère le Top 10 global
    public function getTop10() {
        $stmt = $this->pdo->query("
            SELECT name, best_score, games_played
            FROM players
            ORDER BY best_score ASC
            LIMIT 10
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
