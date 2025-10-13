<?php
class Player {
    public $id;
    public $name;
    public $bestScore;
    public $gamesPlayed = 0;

    public function __construct($name) {
        $this->name = $name;
    }

    public function updateScore($score) {
        $this->bestScore = min($this->bestScore ?? $score, $score);
        $this->gamesPlayed++;
    }
}
