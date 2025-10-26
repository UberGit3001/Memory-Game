<?php
// ==========================================
// 🏆 PAGE : classement.php — Classement global
// ==========================================

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/classes/Scoreboard.php';
require_once __DIR__ . '/includes/header.php';

$scoreboard = new Scoreboard($pdo);
$topPlayers = $scoreboard->getTop10();
?>

<main class="classement-container">
    <h1>🏆 Top 10 des Meilleurs Joueurs</h1>

    <table class="ranking-table">
        <thead>
            <tr>
                <th>Rang</th>
                <th>Joueur</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($topPlayers as $index => $player): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($player['name']) ?></td>
                    <td><?= htmlspecialchars($player['best_score']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn">🏠 Retour à l'accueil</a>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
