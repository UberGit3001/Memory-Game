<?php
// ==========================================
// PAGE : game.php — Lancement d'une partie
// ==========================================

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/classes/Game.php';
require_once __DIR__ . '/classes/Player.php';
require_once __DIR__ . '/classes/Scoreboard.php';
require_once __DIR__ . '/includes/header.php';

// Vérifie que le joueur et le nombre de paires ont été envoyés
if (!isset($_POST['player_name']) || !isset($_POST['pairs_count'])) {
    header('Location: index.php');
    exit;
}

$playerName = htmlspecialchars($_POST['player_name']);
$pairsCount = (int) $_POST['pairs_count'];

// Instancie le joueur
$player = new Player($playerName);

// Démarre une nouvelle partie
$game = new Game($pairsCount);
$cards = $game->cards;

?>
<main class="game-container">
    <h1>Memory Game</h1>
    <h2>Joueur : <?= $player->name ?> | Paires : <?= $pairsCount ?></h2>

    <section class="cards-grid">
        <?php foreach ($cards as $index => $card): ?>
            <div class="card" data-index="<?= $index ?>">
                <div class="card-inner">
                    <div class="card-front">?</div>
                    <div class="card-back">
                        <img src="assets/img/<?= $card->image ?>" alt="Carte <?= $index ?>">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <form action="result.php" method="POST" id="endGameForm" style="display:none;">
        <input type="hidden" name="player_name" value="<?= $player->name ?>">
        <input type="hidden" name="pairs_count" value="<?= $pairsCount ?>">
        <input type="hidden" name="score" id="scoreInput">
    </form>

    <p id="moves">Mouvements : 0</p>
</main>

<script src="assets/js/memory.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
