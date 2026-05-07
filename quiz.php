<?php require_once __DIR__ . '/db.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>🧠 Quiz Typing · KeyDash</title>
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
  font-family:system-ui,-apple-system,Segoe UI,Roboto;
  color:var(--text);
  display:flex;
  align-items:center;
  justify-content:center;
  background:var(--bg);
  overflow:hidden;
  position:relative;
}

/* ---- Animated Background ---- */
.bg{
  position:fixed; inset:0; z-index:-2;
  background:
   radial-gradient(60vw 60vh at 80% -10%, rgba(157,125,255,.18), transparent 55%),
   radial-gradient(50vw 40vh at 10% 110%, rgba(110,168,254,.18), transparent 55%),
   radial-gradient(60vw 50vh at -10% 20%, rgba(66,239,154,.12), transparent 60%),
   linear-gradient(180deg,#060910 0%,#0A0F1B 100%);
}
.bg::before,.bg::after{
  content:"";position:absolute;inset:-20%;
  background:conic-gradient(from 0deg,rgba(110,168,254,.18),rgba(157,125,255,.18),rgba(110,168,254,.18));
  filter:blur(80px);animation:spin 35s linear infinite;
  mix-blend-mode:screen;opacity:.45;
}
.bg::after{animation-duration:55s;animation-direction:reverse;opacity:.3;}
@keyframes spin{to{transform:rotate(360deg)}}

/* Floating blobs */
.blob{
  position:fixed;width:42vmax;height:42vmax;border-radius:50%;
  background:radial-gradient(closest-side,rgba(110,168,254,.22),transparent 70%);
  filter:blur(70px);z-index:-1;opacity:.6;animation:float 20s ease-in-out infinite;
}
.blob.b2{left:-10vmax;top:10vmax;animation-duration:26s}
.blob.b3{right:-8vmax;bottom:-6vmax;animation-duration:30s}
@keyframes float{
  0%,100%{transform:translateY(0) translateX(0)}
  50%{transform:translateY(-40px) translateX(20px)}
}

/* ---- Card ---- */
.card{
  background:rgba(20,28,48,.75);
  border:1px solid rgba(255,255,255,.08);
  padding:40px 50px;
  border-radius:22px;
  text-align:center;
  box-shadow:0 20px 80px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.06);
  position:relative;
  z-index:1;
  animation:fadeIn .8s ease;
  max-width:600px;
}
@keyframes fadeIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:none}}

h1{
  margin-bottom:18px;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  -webkit-background-clip:text;color:transparent;
}
#q{font-size:20px;margin-bottom:14px;min-height:40px;transition:all .3s ease;}
input{
  padding:10px 14px;border-radius:10px;border:none;width:80%;
  font-size:16px;text-align:center;margin-top:10px;
  background:rgba(255,255,255,.06);color:var(--text);
  box-shadow:0 0 15px rgba(110,168,254,.15);
  outline:none;
}
.btn{
  margin-top:16px;
  padding:10px 18px;
  border:none;
  border-radius:12px;
  font-weight:700;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  cursor:pointer;
  color:#0C1220;
  box-shadow:0 8px 24px rgba(110,168,254,.35);
  transition:transform .1s ease,box-shadow .25s ease;
}
.btn:hover{transform:translateY(-1px);box-shadow:0 12px 32px rgba(110,168,254,.45);}
.btn:active{transform:translateY(0);}
#result{margin-top:14px;color:var(--muted);font-size:16px;min-height:24px;transition:.3s}
#scoreBox{margin-top:20px;font-weight:700;font-size:18px}
</style>
</head>
<body>
<!-- Animated layers -->
<div class="bg"></div>
<div class="blob b1" style="left:55%;top:-12%"></div>
<div class="blob b2"></div>
<div class="blob b3"></div>

<!-- Quiz Card -->
<div class="card">
  <h1>🧠 Quiz Typing</h1>
  <p id="q">Loading...</p>
  <input id="ans" autocomplete="off" placeholder="Type your answer & press Enter">
  <div id="result"></div>
  <div id="scoreBox"></div>
  <button class="btn" id="restart" style="display:none">Restart Quiz</button>
</div>

<script>
const allQs=[
  ["Capital of France?","paris"],
  ["5 + 12 = ?","17"],
  ["Color of the sky?","blue"],
  ["HTML stands for?","hyper text markup language"],
  ["Opposite of hot?","cold"],
  ["Planet known as Red Planet?","mars"],
  ["2 x 9 = ?","18"],
  ["Language used for web styling?","css"],
  ["Fastest land animal?","cheetah"],
  ["Water freezes at __°C","0"],
  ["Who painted Mona Lisa?","leonardo da vinci"],
  ["Binary of 5?","101"],
  ["National fruit of India?","mango"],
  ["Chemical symbol of Oxygen?","o"],
  ["CPU stands for?","central processing unit"]
];

// Random 10
const qs = allQs.sort(()=>0.5 - Math.random()).slice(0,10);
let i=0,score=0;
const q=document.getElementById('q'),
      a=document.getElementById('ans'),
      r=document.getElementById('result'),
      s=document.getElementById('scoreBox'),
      restart=document.getElementById('restart');

function show(){
  if(i < qs.length){
    q.innerHTML = `<b>Q${i+1}/${qs.length}:</b> ${qs[i][0]}`;
    r.textContent="";
    s.textContent="";
  } else {
    const percent=(score/qs.length)*100;
    let merit="";
    if(percent>=90) merit="🏅 Excellent!";
    else if(percent>=75) merit="🥈 Great!";
    else if(percent>=50) merit="🙂 Good Try!";
    else merit="😅 Needs Practice!";
    q.innerHTML=`✅ <b>Quiz Completed!</b>`;
    s.innerHTML=`Score: <b>${score}/${qs.length}</b> (${percent.toFixed(1)}%)<br>${merit}`;
    a.style.display="none";
    restart.style.display="inline-block";
  }
}
show();

a.addEventListener('keydown',e=>{
  if(e.key==="Enter" && i < qs.length){
    if(a.value.trim().toLowerCase()===qs[i][1]){
      score++;
      r.textContent="✔ Correct!";
      r.style.color="#42ef9a";
    } else {
      r.textContent=`❌ Correct Answer: ${qs[i][1]}`;
      r.style.color="#FF6B6B";
    }
    i++;
    a.value="";
    setTimeout(show,1000);
  }
});
restart.onclick=()=>window.location.reload();
</script>
</body>
</html>
