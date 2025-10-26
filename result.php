<?php
// ==========================================
// PAGE : result.php — Résultats finaux
// ==========================================

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/classes/Scoreboard.php';
require_once __DIR__ . '/includes/header.php';

if (!isset($_POST['player_name'], $_POST['score'], $_POST['pairs_count'])) {
    header('Location: index.php');
    exit;
}

$playerName = htmlspecialchars($_POST['player_name']);
$score = (int) $_POST['score'];
$pairsCount = (int) $_POST['pairs_count'];

$scoreboard = new Scoreboard($pdo);
$scoreboard->saveScore($playerName, $score, $pairsCount);

?>
<main class="result-container">
    <h1>🎉 Partie terminée !</h1>
    <p>Bravo <strong><?= $playerName ?></strong> 🎖️</p>
    <p>Tu as trouvé <?= $pairsCount ?> paires en <strong><?= $score ?></strong> coups !</p>

    <div class="buttons">
        <a href="index.php" class="btn">🔁 Rejouer</a>
        <a href="classement.php" class="btn">🏆 Voir le classement</a>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
