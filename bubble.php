<?php require_once __DIR__ . '/db.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>💬 Bubble Burst · KeyDash</title>
<style>
:root{
  --bg:#070B14;
  --accent:#6EA8FE;
  --accent2:#9D7DFF;
  --text:#EAF0FF;
  --muted:#9FB0D0;
  --success:#42ef9a;
}
*{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu;
  background:var(--bg);
  color:var(--text);
  overflow:hidden;
  height:100vh;
}

/* ---- Animated Background ---- */
.bg{
  position:fixed;inset:0;z-index:-2;
  background:
    radial-gradient(60vw 60vh at 80% -10%, rgba(157,125,255,.15), transparent 55%),
    radial-gradient(50vw 40vh at 10% 110%, rgba(110,168,254,.15), transparent 55%),
    linear-gradient(180deg,#060910 0%,#0A0F1B 100%);
  animation:bgmove 20s ease-in-out infinite alternate;
}
@keyframes bgmove{
  0%{background-position:0% 0%,100% 100%,center}
  100%{background-position:100% 100%,0% 0%,center}
}
.blob{
  position:fixed;
  width:40vmax;height:40vmax;
  border-radius:50%;
  background:radial-gradient(closest-side,rgba(110,168,254,.15),transparent 70%);
  filter:blur(60px);
  z-index:-1;opacity:.7;
  animation:float 24s ease-in-out infinite;
}
.blob.b2{left:-8vmax;top:20vmax;animation-duration:28s}
.blob.b3{right:-10vmax;bottom:-6vmax;animation-duration:32s}
@keyframes float{
  0%,100%{transform:translateY(0) translateX(0) scale(1)}
  50%{transform:translateY(-40px) translateX(20px) scale(1.05)}
}

/* ---- Header & HUD ---- */
header{
  position:fixed;top:0;left:0;right:0;
  text-align:center;
  padding:14px 0;
  background:rgba(20,28,48,.55);
  backdrop-filter:blur(10px);
  font-weight:700;
  font-size:20px;
  letter-spacing:.5px;
  border-bottom:1px solid rgba(255,255,255,.08);
  animation:fadeIn .8s ease;
}
@keyframes fadeIn{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:none}}

#hud{
  position:fixed;top:60px;left:50%;transform:translateX(-50%);
  display:flex;gap:24px;
  font-weight:600;font-size:16px;color:var(--muted);
  transition:opacity .6s ease;
}
#hud span{color:var(--text);transition:color .3s ease;}

/* ---- Timer Bar ---- */
#timerBar{
  position:fixed;top:46px;left:0;height:4px;width:100%;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  transform-origin:left;
  animation:pulse 1.5s ease-in-out infinite alternate;
}
@keyframes pulse{
  from{opacity:.7;filter:brightness(1)}
  to{opacity:1;filter:brightness(1.3)}
}

/* ---- Input ---- */
#input{
  position:fixed;bottom:25px;left:50%;transform:translateX(-50%);
  width:320px;padding:12px 16px;border:none;
  border-radius:14px;font-size:17px;text-align:center;
  background:rgba(255,255,255,.06);
  color:var(--text);
  outline:none;
  box-shadow:0 0 25px rgba(110,168,254,.25);
  transition:box-shadow .3s ease,transform .2s ease;
}
#input:focus{
  box-shadow:0 0 30px rgba(157,125,255,.45);
  transform:scale(1.03);
}

/* ---- Bubbles ---- */
.bubble{
  position:absolute;
  padding:14px 22px;
  border-radius:50%;
  background:radial-gradient(circle at 30% 30%,rgba(110,168,254,.4),rgba(157,125,255,.3));
  border:1px solid rgba(255,255,255,.12);
  color:#fff;font-weight:600;
  box-shadow:0 0 25px rgba(110,168,254,.25);
  user-select:none;
  animation:floatBubble 10s ease-in forwards;
  transform-origin:center;
  transition:transform .2s ease;
}
@keyframes floatBubble{
  0%{transform:translateY(110vh) scale(1);opacity:.9}
  50%{transform:translateY(50vh) scale(1.05)}
  100%{transform:translateY(-140px) scale(1);opacity:0}
}
.bubble:active{transform:scale(1.1)}

/* ---- Overlay ---- */
#overlay{
  position:fixed;inset:0;
  display:flex;align-items:center;justify-content:center;
  flex-direction:column;
  background:rgba(10,15,30,.75);
  backdrop-filter:blur(12px);
  color:#fff;font-size:28px;font-weight:700;
  opacity:0;visibility:hidden;transition:opacity .6s ease;
}
#overlay.show{opacity:1;visibility:visible;}
.btn{
  margin-top:20px;padding:10px 20px;
  border:none;border-radius:12px;
  font-weight:700;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  color:#0c1220;cursor:pointer;
  box-shadow:0 8px 24px rgba(110,168,254,.35);
  transition:transform .15s ease;
}
.btn:hover{transform:translateY(-2px);}
.btn:active{transform:translateY(0);}
</style>
</head>
<body>
<!-- Animated Background Layers -->
<div class="bg"></div>
<div class="blob b1" style="left:55%;top:-12%"></div>
<div class="blob b2"></div>
<div class="blob b3"></div>

<header>💬 Bubble Burst — 1 Minute Challenge</header>
<div id="timerBar"></div>
<div id="hud">
  <div>⏱ Time: <span id="time">60</span>s</div>
  <div>🏆 Score: <span id="score">0</span></div>
</div>
<input id="input" placeholder="Type the word & press Enter" autocomplete="off">
<div id="overlay">
  <div id="final"></div>
  <button class="btn" onclick="window.location.reload()">Restart</button>
</div>

<script>
const words=["code","speed","type","focus","skill","game","learn","test","smart","fast","logic","brain","sharp","text","input","burst","flow","press","clear","goal"];
const scoreEl=document.getElementById('score');
const timeEl=document.getElementById('time');
const timerBar=document.getElementById('timerBar');
const overlay=document.getElementById('overlay');
const final=document.getElementById('final');
const inp=document.getElementById('input');

let score=0,time=60,active=true;

function spawn(){
  if(!active)return;
  const w=words[Math.floor(Math.random()*words.length)];
  const b=document.createElement('div');
  b.className='bubble';
  b.textContent=w;
  b.style.left=Math.random()*90+'%';
  document.body.appendChild(b);
  setTimeout(()=>b.remove(),10000);
}
const bubbleInterval=setInterval(spawn,1200);

const timer=setInterval(()=>{
  time--;
  timeEl.textContent=time;
  timerBar.style.transform=`scaleX(${time/60})`;
  if(time<=0){
    clearInterval(timer);
    clearInterval(bubbleInterval);
    active=false;
    gameOver();
  }
},1000);

inp.addEventListener('keydown',e=>{
  if(e.key==="Enter"&&active){
    const val=inp.value.trim().toLowerCase();
    const bubbles=[...document.querySelectorAll('.bubble')];
    bubbles.forEach(b=>{
      if(b.textContent.toLowerCase()===val){
        b.style.animation='none';
        b.style.transition='transform .3s ease, opacity .3s ease';
        b.style.transform='scale(1.5)';
        b.style.opacity='0';
        setTimeout(()=>b.remove(),300);
        score++;
        scoreEl.textContent=score;
      }
    });
    inp.value="";
  }
});

function gameOver(){
  overlay.classList.add('show');
  let remark="";
  if(score>=40) remark="🏅 Excellent!";
  else if(score>=25) remark="🥈 Great!";
  else if(score>=15) remark="🙂 Good Try!";
  else remark="😅 Keep Practicing!";
  final.innerHTML=`✨ Time's Up! ✨<br><br>Your Score: <b>${score}</b><br>${remark}`;
}
</script>
</body>
</html>
