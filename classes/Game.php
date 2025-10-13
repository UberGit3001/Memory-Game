<?php
require_once 'Card.php';

class Game {
    public $cards = [];
    public $pairsCount;
    public $moves = 0;

    public function __construct($pairsCount) {
        $this->pairsCount = $pairsCount;
        $this->generateCards();
    }

    private function generateCards() {
        $images = range(1, 20); // exemple d'images disponibles
        shuffle($images);
        $selected = array_slice($images, 0, $this->pairsCount);

        foreach ($selected as $img) {
            $this->cards[] = new Card($img . '_a', $img . '.png');
            $this->cards[] = new Card($img . '_b', $img . '.png');
        }

        shuffle($this->cards);
    }

    public function flipCard($index) {
        $this->cards[$index]->flip();
        $this->moves++;
    }

    public function isComplete() {
        foreach ($this->cards as $card) {
            if (!$card->isMatched) return false;
        }
        return true;
    }
}
