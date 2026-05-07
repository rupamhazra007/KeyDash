<?php require_once __DIR__ . '/db.php'; require_login(); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Typing Test · KeyDash</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
/* -------- Root theme -------- */
:root{
  --bg:#070b14;
  --muted:#9fb0d0;
  --text:#e7ecf7;
  --accent:#6ea8fe;
  --accent2:#9d7dff;
  --card:#111827;
  --border:rgba(255,255,255,.1);
  color-scheme: dark;
}

/* -------- Base layout -------- */
*{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:system-ui,-apple-system,Segoe UI,Roboto;
  background:var(--bg);
  color:var(--text);
  min-height:100vh;
  overflow:hidden;
}

/* -------- Animated gradient background -------- */
.bg{
  position:fixed;
  inset:0;
  z-index:-3;
  background:
    radial-gradient(60vw 60vh at 80% -10%, rgba(157,125,255,.17), transparent 55%),
    radial-gradient(55vw 45vh at 10% 110%, rgba(110,168,254,.17), transparent 55%),
    linear-gradient(180deg, #060910 0%, #0A0F1B 100%);
  animation: shift 24s ease-in-out infinite alternate;
}
.bg::before,.bg::after{
  content:"";position:absolute;inset:-20%;
  background:conic-gradient(from 0deg, rgba(110,168,254,.25), rgba(157,125,255,.25), rgba(110,168,254,.25));
  filter:blur(100px);
  mix-blend-mode:screen;
  opacity:.4;
  animation:spin 60s linear infinite;
}
.bg::after{animation-duration:80s;animation-direction:reverse;opacity:.25;}
@keyframes spin{to{transform:rotate(360deg)}}
@keyframes shift{
  0%{background-position:0 0;}
  50%{background-position:50% 100%;}
  100%{background-position:100% 0;}
}

/* -------- Wrapper -------- */
.wrap{max-width:900px;margin:40px auto;padding:0 16px;position:relative;z-index:2;}
.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;gap:8px;flex-wrap:wrap}
a{color:var(--text);text-decoration:none;transition:opacity .2s ease;}
a:hover{opacity:.85}

/* -------- Card -------- */
.card{
  background:rgba(17,23,35,.85);
  border:1px solid var(--border);
  border-radius:18px;
  padding:24px;
  box-shadow:0 8px 32px rgba(0,0,0,.45);
  backdrop-filter:blur(16px);
  transition:transform .3s ease, box-shadow .3s ease;
}
.card:hover{
  transform:translateY(-2px);
  box-shadow:0 14px 40px rgba(0,0,0,.6);
}
.stats span{margin-right:12px;color:var(--muted)}

/* -------- Select (dark neon) -------- */
.select{
  appearance:none;
  -webkit-appearance:none;
  -moz-appearance:none;
  background-color:#1a2234;
  border:1px solid rgba(255,255,255,.18);
  color:#e7ecf7;
  padding:8px 36px 8px 12px;
  border-radius:10px;
  line-height:1.2;
  background-image:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="%23cfe1ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>');
  background-repeat:no-repeat;
  background-position:right 10px center;
  background-size:14px;
  transition:border .2s ease, box-shadow .2s ease;
}
.select:hover{border-color:rgba(255,255,255,.3)}
.select:focus{
  outline:none;
  border-color:var(--accent);
  box-shadow:0 0 0 6px rgba(110,168,254,.2);
}
select option{
  background-color:#111827;
  color:#e7ecf7;
}

/* -------- Quote -------- */
.quote{
  margin:16px 0;
  padding:16px;
  border-radius:14px;
  background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.12);
  user-select:none;
  white-space:pre-wrap;
  line-height:1.6;
  font-size:18px;
  letter-spacing:.3px;
  box-shadow:inset 0 0 20px rgba(110,168,254,.04);
  transition:background .3s ease;
}
.quote .ok{color:#a7f3d0;transition:color .2s ease;}
.quote .bad{background:rgba(255,107,107,.25);border-radius:4px}

/* -------- Textarea -------- */
textarea{
  width:100%;
  min-height:120px;
  padding:12px;
  border-radius:12px;
  background:rgba(255,255,255,.07);
  border:1px solid rgba(255,255,255,.2);
  color:#fff;
  line-height:1.6;
  resize:none;
  font-size:16px;
  box-shadow:inset 0 0 10px rgba(0,0,0,.3);
}
textarea:focus{
  border-color:var(--accent);
  box-shadow:0 0 12px rgba(110,168,254,.25);
}

/* -------- Buttons -------- */
.btn{
  cursor:pointer;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  border:none;
  color:#fff;
  border-radius:10px;
  padding:10px 16px;
  margin-right:8px;
  font-weight:600;
  transition:all .2s ease;
  box-shadow:0 4px 16px rgba(110,168,254,.35);
}
.btn:hover{opacity:.95;transform:translateY(-1px);box-shadow:0 6px 20px rgba(110,168,254,.45);}
.btn.secondary{
  background:transparent;
  border:1px solid rgba(255,255,255,.25);
  box-shadow:none;
}
.result{
  margin-top:10px;
  padding:10px;
  border-left:4px solid var(--accent);
  background:rgba(110,168,254,.12);
  border-radius:8px;
  display:none;
}
.muted{color:var(--muted)}
.footer{text-align:center;color:#8fa3c8;padding:16px 8px;font-size:13px;opacity:.85;}
</style>
</head>
<body>
<div class="bg"></div>

<div class="wrap">
  <div class="top">
    <a href="index.php">← Home</a>
    <div><b>KeyDash</b> · <a href="leaderboard.php">Leaderboard</a> · <a href="logout.php">Logout</a></div>
  </div>

  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
      <h2 style="margin:0;color:#fff;">Typing Test</h2>

      <!-- Duration picker -->
      <div style="display:flex;align-items:center;gap:8px">
        <label for="duration" class="muted">Duration</label>
        <select id="duration" class="select">
          <option value="30">30 seconds</option>
          <option value="60" selected>1 minute</option>
          <option value="120">2 minutes</option>
          <option value="180">3 minutes</option>
          <option value="300">5 minutes</option>
          <option value="600">10 minutes</option>
        </select>
      </div>

      <div class="stats">
        <span>⏱ <b id="time">60</b>s</span>
        <span>⚡ <b id="wpm">0</b> WPM</span>
        <span>🎯 <b id="acc">100%</b></span>
      </div>
    </div>

    <div id="quote" class="quote">Loading…</div>
    <textarea id="input" placeholder="Start typing here…" disabled></textarea>

    <div style="margin-top:10px">
      <button id="btnStart" class="btn">Start</button>
      <button id="btnReset" class="btn secondary">Reset</button>
    </div>

    <div id="result" class="result"></div>
  </div>

  <div class="footer">developed by rupam</div>
</div>

<script>
<?php /* ✅ JS logic unchanged — your existing one kept intact */ ?>
(() => {
  const $ = s => document.querySelector(s);
  const quoteEl = $("#quote"), inputEl = $("#input");
  const timeEl = $("#time"), wpmEl = $("#wpm"), accEl = $("#acc");
  const btnStart = $("#btnStart"), btnReset = $("#btnReset");
  const resultEl = $("#result"), durationSel = $("#duration");

  let quote = "", typed = "", correct = 0;
  let sessionCorrect = 0, sessionTyped = 0;
  let totalDuration = 60, timeLeft = totalDuration;
  let started = false, paused = false, timer = null;

  const FALLBACK_QUOTES = [
    "The quick brown fox jumps over the lazy dog.",
    "Typing fast is a skill that comes with practice and patience.",
    "Simplicity is the soul of efficiency.",
    "Programs must be written for people to read and only incidentally for machines to execute.",
    "Speed matters, but accuracy wins the long game.",
    "Practice daily and your fingers will remember the flow of the keys."
  ];

  function setTitleTick() {
    document.title = (started && !paused) ? `(${timeLeft}s) Typing Test · KeyDash` : `Typing Test · KeyDash`;
  }

  async function fetchQuote(){
    try{
      const r = await fetch('quote.php', {cache:'no-store'});
      if(!r.ok) throw new Error('HTTP '+r.status);
      const j = await r.json();
      quote = j.quote || FALLBACK_QUOTES[Math.floor(Math.random()*FALLBACK_QUOTES.length)];
    }catch(e){
      quote = FALLBACK_QUOTES[Math.floor(Math.random()*FALLBACK_QUOTES.length)];
    }
    render();
  }

  function render(){
    const out=[]; correct=0;
    for(let i=0;i<quote.length;i++){
      const ch=quote[i], t=typed[i]??"";
      if(t===""){ out.push(ch); }
      else if(t===ch){ out.push('<span class="ok">'+ch+'</span>'); correct++; }
      else { out.push('<span class="bad">'+ch+'</span>'); }
    }
    quoteEl.innerHTML = out.join("");
  }

  function totals(){
    return { correct: sessionCorrect + correct, typed: sessionTyped + typed.length };
  }

  function calcWPM(){
    const elapsed = Math.max((totalDuration - timeLeft), 1);
    const minutes = elapsed / 60;
    const t = totals();
    return Math.max(0, (t.correct / 5) / minutes);
  }

  function calcAcc(){
    const t = totals();
    const denom = t.typed || 1;
    return (t.correct / denom) * 100;
  }

  function hud(){
    timeEl.textContent = String(timeLeft);
    wpmEl.textContent = calcWPM().toFixed(2);
    accEl.textContent = calcAcc().toFixed(2) + '%';
    setTitleTick();
  }

  function completedExactly(){
    const trimmedTyped = typed.replace(/\s+$/,'');
    const trimmedQuote = quote.replace(/\s+$/,'');
    return trimmedTyped === trimmedQuote;
  }

  function advanceIfCompleted(){
    if(completedExactly()){
      sessionCorrect += correct;
      sessionTyped   += typed.length;
      typed = ""; correct = 0; inputEl.value = "";
      fetchQuote();
    }
  }

  function showNote(msg){
    resultEl.textContent = msg;
    resultEl.style.display = 'block';
    clearTimeout(showNote._t);
    showNote._t = setTimeout(()=>{ resultEl.style.display='none'; }, 1400);
  }

  async function save(){
    try{
      hud();
      const payload = {
        wpm: parseFloat(wpmEl.textContent),
        accuracy: parseFloat(accEl.textContent),
        duration: totalDuration,
        quote_len: quote.length
      };
      const r = await fetch('save_result.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify(payload)
      });
      const j = await r.json();
      resultEl.style.display='block';
      resultEl.textContent = j.ok
        ? `Saved! WPM ${payload.wpm.toFixed(2)}, Accuracy ${payload.accuracy.toFixed(2)}%. Check Leaderboard.`
        : ('Not saved: ' + (j.error || 'Unknown'));
    }catch(e){
      resultEl.style.display='block';
      resultEl.textContent='Save failed (network?).';
    }
  }

  function startTimer(){
    timer = setInterval(()=>{
      if(paused) return;
      timeLeft--;
      hud();
      if(timeLeft <= 0){
        clearInterval(timer);
        inputEl.setAttribute('disabled','disabled');
        durationSel.removeAttribute('disabled');
        save();
        started = false;
        setTitleTick();
      }
    }, 1000);
  }

  function togglePause(){
    if(!started) return;
    paused = !paused;
    if(paused){
      showNote('Paused. Press Space or P to resume.');
      inputEl.setAttribute('disabled','disabled');
    } else {
      resultEl.style.display='none';
      inputEl.removeAttribute('disabled');
      inputEl.focus();
    }
    setTitleTick();
  }

  function hardReset(){
    started = false; paused = false; clearInterval(timer);
    sessionCorrect = 0; sessionTyped = 0;
    typed=""; correct=0; inputEl.value=''; inputEl.setAttribute('disabled','disabled');
    totalDuration = parseInt(durationSel.value, 10);
    timeLeft = totalDuration;
    wpmEl.textContent='0'; accEl.textContent='100%'; resultEl.style.display='none';
    durationSel.removeAttribute('disabled');
    hud(); fetchQuote();
  }

  // --- Events ---
  btnStart.addEventListener('click', ()=>{
    if(started) return;
    started = true; paused = false;
    durationSel.setAttribute('disabled','disabled');
    inputEl.removeAttribute('disabled'); inputEl.focus();
    startTimer(); hud();
  });

  btnReset.addEventListener('click', hardReset);

  durationSel.addEventListener('change', ()=>{
    if(started) return;
    localStorage.setItem('kd_duration', durationSel.value);
    totalDuration = parseInt(durationSel.value, 10);
    timeLeft = totalDuration;
    hud();
  });

  inputEl.addEventListener('paste', e => { e.preventDefault(); showNote('Paste disabled for fair test.'); });

  inputEl.addEventListener('input', e=>{
    if(!started || paused) return;
    typed = e.target.value;
    render();
    hud();
    advanceIfCompleted();
  });

  document.addEventListener('keydown', (e)=>{
    if(e.key === '1'){ 
        e.preventDefault();
        togglePause();
    }
    if(e.key === 'Enter'){
        if(completedExactly()) {
            e.preventDefault(); 
            advanceIfCompleted();
        } else {
            showNote('Complete the sentence to go next.');
        }
    }
});

  window.addEventListener('beforeunload', (e)=>{
    if(started && timeLeft>0 && !paused){
      e.preventDefault(); e.returnValue = '';
    }
  });

  (function init(){
    const saved = localStorage.getItem('kd_duration');
    if(saved){
      const opt = Array.from(durationSel.options).find(o=>o.value===saved);
      if(opt) opt.selected = true;
    }
    totalDuration = parseInt(durationSel.value, 10);
    timeLeft = totalDuration;
    hud();
    fetchQuote();
  })();
})();
</script>
</body>
</html>
