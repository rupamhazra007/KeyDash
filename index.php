<?php require_once __DIR__ . '/db.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>KeyDash</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
:root{
  --bg:#070B14;
  --text:#EAF0FF;
  --muted:#9FB0D0;
  --accent:#6EA8FE;
  --accent2:#9D7DFF;
  --glass:rgba(20,28,48,.55);
}
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;scroll-behavior:smooth}
body{
  font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu;
  background:var(--bg);
  color:var(--text);
  overflow-x:hidden;
}

/* 🔮 Animated Background */
.bg{position:fixed;inset:0;z-index:-2;
  background:
    radial-gradient(60vw 60vh at 80% -10%,rgba(157,125,255,.18),transparent 55%),
    radial-gradient(50vw 40vh at 10% 110%,rgba(110,168,254,.18),transparent 55%),
    linear-gradient(180deg,#060910 0%,#0A0F1B 100%);
}
.blob{position:fixed;width:40vmax;height:40vmax;border-radius:50%;
  background:radial-gradient(closest-side,rgba(110,168,254,.18),transparent 70%);
  filter:blur(60px);z-index:-1;opacity:.7;
  animation:float 18s ease-in-out infinite;
}
.blob.b2{left:-10vmax;top:20vmax;animation-duration:24s}
.blob.b3{right:-10vmax;bottom:-6vmax;animation-duration:28s}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-40px)}}

/* 🔝 Navbar */
.nav{
  position:sticky;top:0;z-index:100;
  display:flex;justify-content:space-between;align-items:center;
  padding:14px 28px;
  background:linear-gradient(180deg,rgba(10,15,25,.7),rgba(10,15,25,.4));
  backdrop-filter:blur(12px);
  border-bottom:1px solid rgba(255,255,255,.08);
}
.nav .brand{
  font-size:20px;font-weight:800;letter-spacing:.4px;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  -webkit-background-clip:text;color:transparent;
}
.nav-links{display:flex;gap:20px;align-items:center}
.nav-links a{
  color:var(--text);text-decoration:none;font-weight:500;
  opacity:.9;transition:opacity .25s ease;
}
.nav-links a:hover{opacity:1}
.btn{
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  color:#0b0f1b;padding:8px 14px;border:none;border-radius:10px;
  font-weight:700;cursor:pointer;transition:transform .15s ease;
}
.btn:hover{transform:translateY(-1px)}

/* 👋 User welcome */
.user-badge{
  color:var(--accent);
  font-weight:600;
  font-size:15px;
  margin-right:10px;
  background:rgba(255,255,255,.05);
  padding:6px 12px;
  border-radius:10px;
  border:1px solid rgba(255,255,255,.1);
  backdrop-filter:blur(6px);
}

/* 🧠 Hero */
.container{max-width:960px;margin:0 auto;padding:50px 16px;text-align:center}
.card{
  background:var(--glass);
  border:1px solid rgba(255,255,255,.08);
  border-radius:22px;padding:40px 30px;
  box-shadow:0 20px 60px rgba(0,0,0,.45);
}
h1{
  font-size:clamp(34px,5vw,56px);
  background:linear-gradient(90deg,#fff,#cfe1ff 30%,#e9dfff 60%);
  -webkit-background-clip:text;color:transparent;margin-bottom:10px;
  animation:glow 3s ease-in-out infinite alternate;
}
@keyframes glow {
  from {text-shadow:0 0 10px rgba(110,168,254,.4),0 0 20px rgba(157,125,255,.3);}
  to {text-shadow:0 0 25px rgba(110,168,254,.6),0 0 40px rgba(157,125,255,.5);}
}
.subtitle{
  color:var(--muted);
  font-size:18px;
  min-height:40px;
  transition:opacity .4s ease;
}

/* 🎮 Games Grid */
.games{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
  gap:20px;margin-top:60px;text-align:center}
.game-card{
  background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);
  border-radius:18px;padding:22px;backdrop-filter:blur(8px);
  opacity:0;transform:translateY(40px);transition:all .8s ease;
}
.game-card.visible{opacity:1;transform:translateY(0)}
.game-card h3{margin:0 0 8px}
.game-card p{color:var(--muted);font-size:14px;min-height:40px;margin-bottom:10px}

/* 📄 Sections */
.section{
  text-align:center;
  margin-top:80px;
  background:var(--glass);
  border:1px solid rgba(255,255,255,.08);
  border-radius:22px;
  padding:60px 30px;
  box-shadow:0 12px 40px rgba(0,0,0,.4);
  opacity:0;transform:translateY(40px);
  transition:all .8s ease;
}
.section.visible{opacity:1;transform:translateY(0)}
.section h2{
  font-size:30px;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  -webkit-background-clip:text;color:transparent;margin-bottom:10px;
}
.section p{color:var(--muted);max-width:720px;margin:0 auto;font-size:16px;line-height:1.6}
.features{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin-top:40px}
.feature{
  background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);
  border-radius:16px;padding:20px;opacity:0;transform:translateY(40px);
  transition:all .8s ease;
}
.feature.visible{opacity:1;transform:translateY(0)}
.footer{text-align:center;color:var(--muted);padding:28px 10px;margin-top:80px}
</style>
</head>
<body>
<div class="bg"></div>
<div class="blob b1" style="left:55%;top:-12%"></div>
<div class="blob b2"></div>
<div class="blob b3"></div>

<!-- 🔝 Navbar -->
<header class="nav">
  <div class="brand">KeyDash</div>
  <div class="nav-links">
    <a href="#about">About</a>
    <a href="#features">Features</a>
    <a href="#why">Why Choose Us</a>
    <a href="leaderboard.php">Leaderboard</a>
    <?php if(current_user()): ?>
      <span class="user-badge">👋 Welcome, <?= htmlspecialchars(current_user()['name']) ?></span>
      <a href="logout.php" class="btn">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php" class="btn">Register</a>
    <?php endif; ?>
  </div>
</header>

<!-- 🧠 Hero -->
<main class="container">
  <section class="card">
    <h1>Boost your typing speed.</h1>
    <p class="subtitle" id="subtitle"></p>
    <?php if(current_user()): ?>
      <div style="margin-top:20px;">
        <a href="test.php?duration=30" class="btn">Start Typing Test</a>
      </div>
    <?php else: ?>
      <div style="margin-top:20px;">
        <a href="register.php" class="btn">Create Account</a>
      </div>
    <?php endif; ?>
  </section>

  <!-- 🎮 Games -->
  <section class="games">
    <div class="game-card"><h3>🧠 Quiz Typing</h3><p>Type the correct answer to fun trivia!</p><a href="quiz.php" class="btn">Play Quiz</a></div>
    <div class="game-card"><h3>💬 Bubble Burst</h3><p>Pop floating words before they vanish!</p><a href="bubble.php" class="btn">Play Game</a></div>
    <div class="game-card"><h3>⚡ Reflex Test</h3><p>Hit the key as soon as the color changes!</p><a href="reflex.php" class="btn">Try Reflex</a></div>
    <div class="game-card"><h3>🔤 Word Memory</h3><p>Memorize and retype words correctly!</p><a href="memory.php" class="btn">Play Memory</a></div>
  </section>

  <!-- 🧩 About -->
  <section class="section" id="about">
    <h2>About KeyDash</h2>
    <p>KeyDash is a fun and interactive typing platform that helps you boost your speed and accuracy with real-time analytics and mini-games — all in one beautiful dashboard.</p>
  </section>

  <!-- ⚙️ Features -->
  <section class="section" id="features">
    <h2>Features</h2>
    <div class="features">
      <div class="feature"><h3>📊 Real-Time Stats</h3><p>Track WPM, accuracy, and progress instantly.</p></div>
      <div class="feature"><h3>🎮 Fun Mini Games</h3><p>Sharpen your mind with exciting typing challenges.</p></div>
      <div class="feature"><h3>🏆 Global Leaderboard</h3><p>Compete with typists from all around the world.</p></div>
    </div>
  </section>

  <!-- 💡 Why Choose Us -->
  <section class="section" id="why">
    <h2>Why Choose KeyDash?</h2>
    <div class="features">
      <div class="feature"><h3>🌟 Beautiful & Simple</h3><p>Modern glassy UI designed to relax your eyes.</p></div>
      <div class="feature"><h3>⚡ Fast & Lightweight</h3><p>No installs, no ads — just start typing instantly.</p></div>
      <div class="feature"><h3>🔒 Secure Progress</h3><p>Your data stays private and synced with your account.</p></div>
    </div>
  </section>
</main>

<footer class="footer">© <?= date('Y') ?> KeyDash · Practice daily, type smarter.</footer>

<!-- ✨ Animations -->
<script>
// Fade-in animations on scroll
const observer=new IntersectionObserver(entries=>{
  entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');});
},{threshold:0.2});
document.querySelectorAll('.section,.feature,.game-card').forEach(el=>observer.observe(el));

// Typewriter subtitle animation
const subtitle=document.getElementById("subtitle");
const lines=[
  "Measure <b>WPM</b> & <b>Accuracy</b> in real time.",
  "Practice daily — watch your progress grow.",
  "Speed matters, but accuracy wins the race.",
  "Ready? Try a fun <b>Typing Game!</b>"
];
let i=0;
function typeText(text,callback){
  subtitle.innerHTML="";let j=0;
  const span=document.createElement("span");
  subtitle.appendChild(span);
  const interval=setInterval(()=>{
    span.innerHTML=text.slice(0,++j);
    if(j>=text.length){clearInterval(interval);setTimeout(callback,1200);}
  },30);
}
function loop(){
  typeText(lines[i],()=>{i=(i+1)%lines.length;loop();});
}
setTimeout(loop,1000);
</script>
</body>
</html>
