<?php
class Card {
    public $id;
    public $image;
    public $isFlipped = false;
    public $isMatched = false;

    public function __construct($id, $image) {
        $this->id = $id;
        $this->image = $image;
    }

    public function flip() {
        $this->isFlipped = !$this->isFlipped;
    }

    public function match() {
        $this->isMatched = true;
    }
}
