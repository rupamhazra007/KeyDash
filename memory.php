<?php require_once __DIR__ . '/db.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>🔤 Word Memory · KeyDash</title>
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
*{box-sizing:border-box;margin:0;padding:0}
body{
  height:100vh;
  font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu;
  color:var(--text);
  display:flex;
  align-items:center;
  justify-content:center;
  background:var(--bg);
  overflow:hidden;
  position:relative;
}

/* ---------- Animated Aurora Background ---------- */
.bg{
  position:fixed; inset:0; z-index:-2; overflow:hidden;
  background:
   radial-gradient(60vw 60vh at 80% -10%, rgba(157,125,255,.18), transparent 55%),
   radial-gradient(50vw 40vh at 10% 110%, rgba(110,168,254,.18), transparent 55%),
   radial-gradient(60vw 50vh at -10% 20%, rgba(66,239,154,.12), transparent 60%),
   linear-gradient(180deg, #060910 0%, #0A0F1B 100%);
}
.bg::before, .bg::after{
  content:""; position:absolute; inset:-20%;
  background:conic-gradient(from 0deg, rgba(110,168,254,.18), rgba(157,125,255,.18), rgba(110,168,254,.18));
  filter:blur(80px); animation:spin 35s linear infinite;
  mix-blend-mode:screen; opacity:.45;
}
.bg::after{animation-duration:55s;animation-direction:reverse;opacity:.3;}
@keyframes spin{to{transform:rotate(360deg)}}

/* Floating blobs */
.blob{
  position:fixed;width:42vmax;height:42vmax;border-radius:50%;
  background:radial-gradient(closest-side,rgba(110,168,254,.2),transparent 70%);
  filter:blur(70px);z-index:-1;opacity:.6;animation:float 20s ease-in-out infinite;
}
.blob.b2{left:-10vmax;top:10vmax;animation-duration:26s}
.blob.b3{right:-8vmax;bottom:-6vmax;animation-duration:30s}
@keyframes float{
  0%,100%{transform:translateY(0) translateX(0)}
  50%{transform:translateY(-40px) translateX(20px)}
}

/* ---------- Card ---------- */
.card{
  background:rgba(20,28,48,.75);
  border:1px solid rgba(255,255,255,.08);
  padding:40px 50px;
  border-radius:20px;
  text-align:center;
  box-shadow:0 20px 80px rgba(0,0,0,.45), inset 0 1px 0 rgba(255,255,255,.06);
  position:relative;
  z-index:1;
  animation:fadeIn .8s ease;
}
@keyframes fadeIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:none}}
h1{
  margin-bottom:20px;
  background:linear-gradient(90deg,#6EA8FE,#9D7DFF);
  -webkit-background-clip:text;color:transparent;
}
#word{
  font-size:28px;font-weight:700;letter-spacing:1px;
  min-height:60px;transition:all .3s ease;
}
input{
  padding:10px 14px;border-radius:10px;border:none;width:80%;
  font-size:16px;margin-top:12px;text-align:center;
  background:rgba(255,255,255,.06);color:var(--text);outline:none;
  box-shadow:0 0 15px rgba(110,168,254,.15);
}
.btn{
  margin-top:18px;padding:10px 18px;border:none;border-radius:12px;
  font-weight:700;background:linear-gradient(90deg,var(--accent),var(--accent2));
  cursor:pointer;color:#0c1220;
  box-shadow:0 8px 24px rgba(110,168,254,.35);
  transition:transform .15s ease, box-shadow .25s ease;
}
.btn:hover{transform:translateY(-1px);box-shadow:0 12px 32px rgba(110,168,254,.45)}
.btn:active{transform:translateY(0) scale(.99)}
#res{margin-top:14px;color:var(--muted);font-size:16px;min-height:24px}
</style>
</head>
<body>
<!-- Background Layers -->
<div class="bg"></div>
<div class="blob b1" style="left:55%; top:-12%"></div>
<div class="blob b2"></div>
<div class="blob b3"></div>

<!-- Main Game Card -->
<div class="card">
  <h1>🔤 Word Memory Challenge</h1>
  <p id="word">Click Start to Begin</p>
  <input id="ans" placeholder="Type the words in reverse order" style="display:none">
  <button class="btn" id="start">Start</button>
  <p id="res"></p>
</div>

<script>
const w=document.getElementById('word'),
      a=document.getElementById('ans'),
      r=document.getElementById('res'),
      s=document.getElementById('start');

const allWords=["keyboard","focus","accuracy","typing","memory","challenge","speed","logic","test","brain","swift","learn","smart","react","skill","power"];
let sequence=[],index=0,phase="idle";

s.onclick=()=>startGame();

function startGame(){
  sequence=[];index=0;r.textContent="";
  a.style.display="none";s.disabled=true;phase="show";
  w.style.color="#EAF0FF";
  const shuffled=[...allWords].sort(()=>0.5-Math.random());
  sequence=shuffled.slice(0,5);
  showNextWord();
}

function showNextWord(){
  if(index<sequence.length){
    w.textContent=sequence[index];
    index++;
    setTimeout(showNextWord,1000);
  } else {
    w.textContent="Now type all 5 words (reverse order)";
    phase="input";
    a.style.display="block";
    a.value="";
    a.focus();
  }
}

a.addEventListener('keydown',e=>{
  if(e.key==="Enter" && phase==="input"){
    const inputWords=a.value.trim().toLowerCase().split(/\s+/);
    const correctSeq=[...sequence].reverse();
    if(JSON.stringify(inputWords)===JSON.stringify(correctSeq)){
      w.textContent="✔ Perfect Memory!";
      w.style.color="#42ef9a";
      r.textContent="You typed all words correctly!";
    } else {
      w.textContent="❌ Wrong Order!";
      w.style.color="#ff6b6b";
      r.textContent=`Correct sequence was: ${correctSeq.join(" ")}`;
    }
    a.style.display="none";
    s.disabled=false;
    s.textContent="Play Again";
    phase="done";
  }
});
</script>
</body>
</html>
