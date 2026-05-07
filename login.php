<?php require_once __DIR__ . '/db.php';
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=strtolower(trim($_POST['email']??'')); $pass=$_POST['password']??'';
  if(!$email||!$pass){$err='Email & password required.';}
  else{
    $st=$pdo->prepare("SELECT * FROM users WHERE email=?"); $st->execute([$email]); $u=$st->fetch();
    if($u && password_verify($pass,$u['password_hash'])){ login_user($u); header('Location:index.php');
 exit; }
    else{$err='Invalid credentials.';}
  }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Login · KeyDash</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
:root{
  --bg:#070B14;
  --text:#EAF0FF;
  --muted:#9FB0D0;
  --accent:#6EA8FE;
  --accent2:#9D7DFF;
  --danger:#ff6b6b;
  color-scheme:dark;
}
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;scroll-behavior:smooth}
body{
  font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial;
  color:var(--text);
  background:var(--bg);
  overflow-x:hidden;
  animation:fadeIn 1s ease-out forwards;
}
@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}

/* 🔮 Background */
.bg{position:fixed;inset:0;z-index:-3;background:
  radial-gradient(60vw 60vh at 80% -10%, rgba(157,125,255,.17), transparent 55%),
  radial-gradient(55vw 45vh at 10% 110%, rgba(110,168,254,.17), transparent 55%),
  linear-gradient(180deg, #060910 0%, #0A0F1B 100%);
  animation:bgmove 20s ease-in-out infinite alternate;
}
@keyframes bgmove{from{background-position:0 0}to{background-position:100% 100%}}

/* 🧠 Topbar */
.topbar{
  position:fixed;top:0;left:0;right:0;height:56px;
  display:flex;align-items:center;justify-content:space-between;
  padding:0 18px;
  background:rgba(10,16,27,.55);
  backdrop-filter:blur(8px);
  border-bottom:1px solid rgba(255,255,255,.08);
  z-index:10;
  animation:navDown .8s ease .2s both;
}
@keyframes navDown{from{opacity:0;transform:translateY(-15px)}to{opacity:1;transform:none}}
.brand{font-weight:800;display:flex;align-items:center;gap:8px}
.brand .dot{width:10px;height:10px;border-radius:50%;
  background:linear-gradient(90deg,var(--accent),var(--accent2));box-shadow:0 0 12px var(--accent)}
.topbar a{color:var(--text);text-decoration:none;opacity:.9}

/* ⚡ Layout */
.wrap{min-height:100vh;padding-top:56px}
.center{max-width:1150px;margin:0 auto;padding:20px 16px;display:grid;gap:30px;align-items:start}
@media(min-width:960px){.center{grid-template-columns:520px 1fr}}

/* 🎯 Login Card */
.card{
  background:rgba(17,23,35,.85);
  border-radius:22px;
  border:1px solid rgba(255,255,255,.08);
  box-shadow:0 18px 60px rgba(0,0,0,.45);
  padding:26px 24px;
  backdrop-filter:blur(12px);
  opacity:0;transform:translateY(50px) scale(.96);
  animation:cardUp 1s ease .3s forwards;
}
@keyframes cardUp{
  0%{opacity:0;transform:translateY(50px) scale(.96);}
  70%{opacity:1;transform:translateY(-4px) scale(1.02);}
  100%{opacity:1;transform:translateY(0) scale(1);}
}
.card.shake{animation:shake .35s ease both!important}
@keyframes shake{
  10%,90%{transform:translateX(-2px)}
  20%,80%{transform:translateX(3px)}
  30%,50%,70%{transform:translateX(-4px)}
  40%,60%{transform:translateX(4px)}
}
h2{margin:8px 0 16px;font-size:26px}

/* 🧾 Form */
.form{display:grid;gap:14px}
.fg{position:relative}
input{
  width:100%;padding:14px 44px 14px 14px;border-radius:12px;
  border:1px solid rgba(255,255,255,.16);
  background:rgba(255,255,255,.06);
  color:var(--text);
  transition:border .2s, box-shadow .2s, background .2s;
}
input:focus{border-color:var(--accent);box-shadow:0 0 0 6px rgba(110,168,254,.18);background:rgba(255,255,255,.08)}
label{
  position:absolute;left:14px;top:12px;color:var(--muted);font-size:14px;pointer-events:none;
  transition:all .15s ease;opacity:.9;
}
input:not(:placeholder-shown)+label,input:focus+label{
  top:-9px;left:10px;font-size:12px;background:rgba(17,23,35,.95);padding:0 5px;border-radius:6px;opacity:.85;
}

/* 👁️ Toggle */
.toggle{position:absolute;right:10px;top:50%;transform:translateY(-50%);
  width:34px;height:34px;border-radius:10px;border:1px solid rgba(255,255,255,.16);
  background:rgba(255,255,255,.06);display:grid;place-items:center;cursor:pointer}
.toggle:hover{border-color:rgba(255,255,255,.3)}
.eye{width:18px;height:18px}

/* 🔘 Button */
.btn{
  margin-top:6px;padding:12px 16px;border:none;border-radius:12px;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  color:#0c1220;font-weight:700;cursor:pointer;
  box-shadow:0 10px 24px rgba(110,168,254,.35);
  transition:transform .15s ease, box-shadow .2s ease;
}
.btn:hover{transform:translateY(-1px);box-shadow:0 14px 28px rgba(110,168,254,.45);}
.spinner{width:16px;height:16px;border:2px solid rgba(12,18,32,.35);border-top-color:#0c1220;border-radius:50%;animation:spin .6s linear infinite;margin-right:8px;display:inline-block}
@keyframes spin{to{transform:rotate(360deg)}}

/* ⚙️ Alert */
.alert{background:rgba(255,107,107,.12);border:1px solid rgba(255,107,107,.55);color:#ffd1d1;padding:10px;border-radius:10px;margin-bottom:10px}

/* 📜 Right info panel */
.right{display:flex;flex-direction:column;gap:16px;opacity:0;animation:fadeUp .9s ease .7s forwards}
@keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:none}}
.section{
  background:rgba(20,28,48,.55);
  border:1px solid rgba(255,255,255,.08);
  border-radius:16px;
  padding:18px 16px;
  box-shadow:0 8px 30px rgba(0,0,0,.25);
  backdrop-filter:blur(10px);
}
.section h3{margin-bottom:10px;font-size:18px;background:linear-gradient(90deg,var(--accent),var(--accent2));-webkit-background-clip:text;color:transparent}
.section p,.section li{color:var(--muted);line-height:1.6}
.section ul{margin:6px 0 0 16px}
.faq .item{border-top:1px solid rgba(255,255,255,.08);padding:8px 0}
.faq .q{display:flex;justify-content:space-between;cursor:pointer}
.faq .a{display:none;margin-top:6px;color:var(--muted)}
.faq .item.open .a{display:block}
.footer{text-align:center;color:#8fa3c8;font-size:13px;margin-top:24px;opacity:.9;animation:fadeUp 1s ease 1.2s both}
</style>
</head>
<body>
<div class="bg"></div>

<div class="topbar">
  <div class="brand"><span class="dot"></span><a href="index.php">KeyDash</a></div>
  <a href="index.php">← Home</a>
</div>

<div class="wrap">
  <div class="center">
    <!-- Left Login -->
    <div class="card <?php if($err) echo 'shake'; ?>" id="card">
      <h2>Welcome back</h2>
      <?php if($err): ?><div class="alert"><?= htmlspecialchars($err) ?></div><?php endif; ?>
      <form method="post" class="form" id="loginForm" autocomplete="off" novalidate>
        <div class="fg">
          <input id="email" name="email" type="email" required placeholder=" " value="<?= htmlspecialchars($_POST['email']??'') ?>">
          <label for="email">Email address</label>
        </div>
        <div class="fg">
          <input id="password" name="password" type="password" required placeholder=" ">
          <label for="password">Password</label>
          <button type="button" class="toggle" id="togglePw">
            <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="#cfe1ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
        <button class="btn" id="btnSubmit" type="submit"><span id="btnText">Login</span></button>
        <p style="color:var(--muted);margin:6px 2px 0">No account? <a href="register.php" style="color:#fff">Register</a></p>
      </form>
    </div>

    <!-- Right Info -->
    <div class="right">
      <div class="section">
        <h3>Why KeyDash?</h3>
        <ul>
          <li>Measure WPM & accuracy with real-time highlight.</li>
          <li>Custom duration: 30s to 10m — your pace.</li>
          <li>Auto-next sentences for continuous flow.</li>
          <li>Leaderboard to compete & improve.</li>
        </ul>
      </div>

      <div class="section">
        <h3>Quick Tips to Improve</h3>
        <ul>
          <li>Keep eyes on the quote — trust muscle memory.</li>
          <li>Sit straight, relax shoulders; don’t chase speed early.</li>
          <li>Focus on accuracy first; speed follows.</li>
          <li>Practice short 30–60s bursts daily.</li>
        </ul>
      </div>

      <div class="section">
        <h3>Privacy & Security</h3>
        <p>We store only essential stats (WPM, accuracy, session time). Passwords are hashed using industry-standard algorithms. Your results are tied to your account so you can track progress.</p>
      </div>

      <div class="section faq" id="faq">
        <h3>FAQ</h3>
        <div class="item"><div class="q"><span>Can I pause a test?</span><b>+</b></div><div class="a">Yes, press <b>P</b> or <b>Space</b> to pause/resume.</div></div>
        <div class="item"><div class="q"><span>How is WPM calculated?</span><b>+</b></div><div class="a">Standard formula: (correct chars / 5) / minutes.</div></div>
        <div class="item"><div class="q"><span>Is paste allowed?</span><b>+</b></div><div class="a">No. Pasting is disabled to ensure fair testing.</div></div>
      </div>
    </div>
  </div>

  <div class="footer">Developed By Rupam</div>
</div>

<script>
/* Toggle Password */
const pw=document.getElementById('password');
const btn=document.getElementById('togglePw');
let shown=false;
btn.onclick=()=>{shown=!shown;pw.type=shown?'text':'password';
btn.querySelector('.eye').setAttribute('stroke',shown?'#42ef9a':'#cfe1ff');};

/* Submit animation */
const form=document.getElementById('loginForm');
const btnSubmit=document.getElementById('btnSubmit');
const btnText=document.getElementById('btnText');
form.addEventListener('submit',()=>{
  if(!form.email.value||!form.password.value)return;
  btnSubmit.disabled=true;
  btnText.innerHTML='<span class="spinner"></span>Logging in…';
});

/* FAQ */
document.querySelectorAll('.faq .q').forEach(q=>{
  q.onclick=()=>{
    q.parentElement.classList.toggle('open');
    q.querySelector('b').textContent=q.parentElement.classList.contains('open')?'–':'+';
  };
});
</script>
</body>
</html>
