    <?php    
    require_once __DIR__ . '/includes/header.php';
    ?>
    
    <header class="toolbar">
      <h1>Memory Game</h1>
      <div class="controls">
        <label>Pairs:
          <select id="pairsSelect">
            <!-- 3..12 -->
            <script>for(let i=3;i<=12;i++){document.write(`<option value="${i}">${i}</option>`)}</script>
          </select>
        </label>
        <button id="startBtn">Démarrer</button>
      </div>
    </header>

    <section class="status">
      <div class="stat"><strong>Coups :</strong> <span id="moves">0</span></div>
      <div class="stat"><strong>Temps :</strong> <span id="time">0</span>s</div>
    </section>

    <section id="board" class="board" aria-live="polite"></section>

    <section class="end-modal hidden" id="endModal">
      <div class="card end-card">
        <h2>Partie terminée</h2>
        <p>Coups : <span id="finalMoves"></span></p>
        <p>Temps : <span id="finalTime"></span>s</p>
        <label>Ton pseudo: <input id="playerName" placeholder="ex: SebNono"></label>
        <div class="actions">
          <button id="saveBtn">Enregistrer le score</button>
          <button id="playAgainBtn">Rejouer</button>
        </div>
        <div id="saveMsg" class="save-msg"></div>
      </div>
    </section>

    <aside class="leaderboard">
      <h3>Top 10</h3>
      <ol id="top10List"><li>—</li></ol>
    </aside>

<?php 
 require_once __DIR__ . '/includes/footer.php';  
?>