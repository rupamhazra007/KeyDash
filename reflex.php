<?php require_once __DIR__ . '/db.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>⚡ Reflex Test · KeyDash</title>
<style>
:root{
  --bg:#070B14;
  --card:#0E1525CC;
  --text:#EAF0FF;
  --accent:#6EA8FE;
  --accent2:#9D7DFF;
  --muted:#9FB0D0;
  --success:#42ef9a;
  --danger:#ff6b6b;
}
*{box-sizing:border-box}
body{
  margin:0;
  display:flex;
  align-items:center;
  justify-content:center;
  flex-direction:column;
  height:100vh;
  background:var(--bg);
  color:var(--text);
  font-family:system-ui,-apple-system,Segoe UI,Roboto;
  overflow:hidden;
}

/* --- Title --- */
h1{
  margin-bottom:18px;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  -webkit-background-clip:text;
  color:transparent;
  font-size:32px;
  letter-spacing:.5px;
}

/* --- Main Box --- */
#box{
  width:240px;
  height:240px;
  background:rgba(20,28,48,.8);
  border:1px solid rgba(255,255,255,.1);
  border-radius:24px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:18px;
  font-weight:600;
  cursor:pointer;
  user-select:none;
  box-shadow:0 0 40px rgba(110,168,254,.15);
  transition:background .3s ease,transform .2s ease;
}
#box.active{background:var(--success);color:#0b1220;transform:scale(1.05);}
#box.early{background:var(--danger);color:#fff;}
#box.wait{background:rgba(20,28,48,.8);}

/* --- HUD --- */
#hud{
  margin-top:20px;
  color:var(--muted);
  font-size:16px;
  text-align:center;
}

/* --- Result overlay --- */
#overlay{
  position:fixed;
  inset:0;
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
  background:rgba(0,0,0,.65);
  backdrop-filter:blur(10px);
  opacity:0;
  visibility:hidden;
  transition:opacity .4s ease;
}
#overlay.show{opacity:1;visibility:visible;}
#overlay h2{
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  -webkit-background-clip:text;
  color:transparent;
  font-size:28px;
  margin-bottom:10px;
}
#overlay p{color:var(--muted);margin:6px 0;font-size:16px}
.btn{
  margin-top:18px;
  padding:10px 18px;
  border:none;
  border-radius:12px;
  font-weight:700;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  color:#0b1220;
  cursor:pointer;
  box-shadow:0 8px 20px rgba(110,168,254,.3);
  transition:transform .1s ease;
}
.btn:hover{transform:translateY(-1px);}
.btn:active{transform:translateY(0);}
</style>
</head>
<body>
<h1>⚡ Reflex Challenge</h1>
<div id="box" class="wait">Wait for green...</div>
<div id="hud">Attempt <span id="attempt">1</span>/10</div>

<div id="overlay">
  <h2>Test Complete</h2>
  <p id="stats"></p>
  <button class="btn" onclick="window.location.reload()">Try Again</button>
</div>

<script>
const box=document.getElementById('box'),
      hud=document.getElementById('hud'),
      att=document.getElementById('attempt'),
      overlay=document.getElementById('overlay'),
      stats=document.getElementById('stats');

let start,timeout,attempt=1,times=[],waiting=true;

function ready(){
  waiting=true;
  box.className='wait';
  box.textContent="Wait for green...";
  hud.style.color="#9FB0D0";
  timeout=setTimeout(()=>{
    box.className='active';
    box.textContent="CLICK!";
    start=Date.now();
    waiting=false;
  },Math.random()*3000+1200);
}

box.onclick=()=>{
  if(waiting){
    clearTimeout(timeout);
    box.className='early';
    box.textContent="Too early!";
    hud.style.color="#ff6b6b";
    setTimeout(ready,1200);
  }
  else{
    const t=Date.now()-start;
    times.push(t);
    hud.style.color="#42ef9a";
    box.textContent=`${t} ms`;
    attempt++;
    if(attempt<=10){
      att.textContent=attempt;
      setTimeout(ready,1500);
    } else endGame();
  }
};

function endGame(){
  const avg = Math.round(times.reduce((a,b)=>a+b,0)/times.length);
  const min = Math.min(...times);
  let merit="";
  if(avg<=220) merit="🏅 Excellent Reflexes!";
  else if(avg<=300) merit="🥈 Great!";
  else if(avg<=400) merit="🙂 Decent!";
  else merit="😅 Needs Practice!";
  stats.innerHTML=`Fastest: <b>${min} ms</b><br>Average: <b>${avg} ms</b><br>${merit}`;
  overlay.classList.add('show');
}

ready();
</script>
</body>
</html>
