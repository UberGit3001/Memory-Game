// js/app.js
document.addEventListener('DOMContentLoaded', ()=>{
  const board = document.getElementById('board');
  const startBtn = document.getElementById('startBtn');
  const pairsSelect = document.getElementById('pairsSelect');
  const movesEl = document.getElementById('moves');
  const timeEl = document.getElementById('time');
  const endModal = document.getElementById('endModal');
  const finalMoves = document.getElementById('finalMoves');
  const finalTime = document.getElementById('finalTime');
  const playerName = document.getElementById('playerName');
  const saveBtn = document.getElementById('saveBtn');
  const playAgainBtn = document.getElementById('playAgainBtn');
  const saveMsg = document.getElementById('saveMsg');
  const top10List = document.getElementById('top10List');

  // state
  let cards = [], first=null, second=null, moves=0, matched=0, totalPairs=6;
  let timerInterval=null, seconds=0, started=false;

  function startGame(){
    totalPairs = parseInt(pairsSelect.value,10);
    initCards(totalPairs);
    renderBoard();
    moves=0; seconds=0; matched=0; first=second=null;
    movesEl.textContent = moves;
    timeEl.textContent = seconds;
    clearInterval(timerInterval);
    started=true;
  }

  function initCards(pairs){
    // generate pairs with simple icons (letters). Could be replaced by images.
    const icons = 'ABCDEFGHIJKLMNOPQRSTUVWX'.split('');
    const chosen = icons.slice(0,pairs);
    cards = [];
    chosen.forEach((v)=>{
      cards.push({id:cryptoRandom(),val:v,matched:false});
      cards.push({id:cryptoRandom(),val:v,matched:false});
    });
    shuffle(cards);
  }

  function cryptoRandom(){ return Math.floor(Math.random()*1e9) }

  function shuffle(array){
    for(let i=array.length-1;i>0;i--){
      const j = Math.floor(Math.random()*(i+1));
      [array[i],array[j]] = [array[j],array[i]];
    }
  }

  function renderBoard(){
    board.innerHTML = '';
    // set grid columns based on total pairs: we keep cards per row = ceil(sqrt(cards))
    const cardCount = cards.length;
    const cols = Math.min(6, Math.max(3, Math.ceil(Math.sqrt(cardCount))));
    board.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;

    cards.forEach(c=>{
      const el = document.createElement('div');
      el.className = 'card';
      el.dataset.id = c.id;
      el.innerHTML = `
        <div class="card-inner">
          <div class="card-face card-front"></div>
          <div class="card-face card-back">${c.val}</div>
        </div>
      `;
      el.addEventListener('click', ()=>onCardClick(c, el));
      board.appendChild(el);
    });
  }

  function onCardClick(card, el){
    if(card.matched) return;
    if(el.classList.contains('flipped')) return;
    if(!started) startTimer();

    // flip visual
    el.classList.add('flipped');

    if(!first){
      first = {card,el};
      return;
    }
    if(first.card.id === card.id) return; // same card clicked twice
    second = {card,el};
    moves++;
    movesEl.textContent = moves;

    // check match
    if(first.card.val === second.card.val){
      // match
      first.card.matched = true;
      second.card.matched = true;
      matched += 2;
      first = second = null;
      // win?
      if(matched === cards.length){
        endGame();
      }
    } else {
      // not matched: flip back after delay
      setTimeout(()=>{
        first.el.classList.remove('flipped');
        second.el.classList.remove('flipped');
        first = second = null;
      }, 700);
    }
  }

  function startTimer(){
    if(timerInterval) clearInterval(timerInterval);
    seconds = 0;
    timeEl.textContent = seconds;
    timerInterval = setInterval(()=>{
      seconds++;
      timeEl.textContent = seconds;
    },1000);
  }

  function endGame(){
    clearInterval(timerInterval);
    finalMoves.textContent = moves;
    finalTime.textContent = seconds;
    endModal.classList.remove('hidden');
    // prefill name if localStorage
    playerName.value = localStorage.getItem('memory_player')||'';
    fetchTop10(); // rafraîchir top10
  }

  // save score to server (PHP)
  saveBtn.addEventListener('click', ()=>{
    const name = (playerName.value||'Anon').trim();
    if(!name){ saveMsg.textContent = 'Indique ton pseudo.'; return; }
    localStorage.setItem('memory_player', name);
    saveMsg.textContent = 'Enregistrement...';

    // payload: name, score = moves, pairs_count, time_seconds
    fetch('php/save_score.php', {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({
        name, score: moves, pairs_count: totalPairs, time_seconds: seconds
      })
    }).then(r=>r.json()).then(data=>{
      if(data.success){
        saveMsg.textContent = 'Score enregistré ✅';
        fetchTop10();
      } else {
        saveMsg.textContent = 'Erreur: '+(data.error||'unknown');
      }
    }).catch(e=>{
      saveMsg.textContent = 'Erreur réseau';
      console.error(e);
    });
  });

  playAgainBtn.addEventListener('click', ()=>{
    endModal.classList.add('hidden');
    startGame();
  });

  startBtn.addEventListener('click', ()=>startGame());

  // simple top10 fetch
  function fetchTop10(){
    fetch('php/get_top10.php').then(r=>r.json()).then(data=>{
      top10List.innerHTML = '';
      if(Array.isArray(data)){
        data.forEach(row=>{
          const li = document.createElement('li');
          li.textContent = `${row.player_name} — ${row.best_score} (${row.games_played} parties)`;
          top10List.appendChild(li);
        });
      } else {
        top10List.innerHTML = '<li>—</li>';
      }
    }).catch(e=>{
      top10List.innerHTML = '<li>Erreur</li>';
    });
  }

  // auto-start
  fetchTop10();
});
