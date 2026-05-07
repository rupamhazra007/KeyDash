<?php require_once __DIR__ . '/db.php';
$err=''; $success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['name']??'');
  $email=strtolower(trim($_POST['email']??''));
  $pass=$_POST['password']??'';
  
  if(!$name||!$email||!$pass){$err='All fields are required.';}
  elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){$err='Invalid email.';}
  elseif(strlen($pass)<6){$err='Password must be at least 6 chars.';}
  else{
    $st=$pdo->prepare("SELECT id FROM users WHERE email=?"); 
    $st->execute([$email]);
    if($st->fetch()){$err='Email already registered.';}
    else{
      $hash=password_hash($pass,PASSWORD_BCRYPT);
      $st=$pdo->prepare("INSERT INTO users(name,email,password_hash) VALUES(?,?,?)");
      $st->execute([$name,$email,$hash]);
      $success='✅ Successfully Registered! You can now login.';
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Register · KeyDash</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
:root{
  --bg:#060914;
  --text:#EAF0FF;
  --muted:#9FB0D0;
  --accent:#6EA8FE;
  --accent2:#9D7DFF;
  --danger:#ff6b6b;
  --ok:#42ef9a;
  color-scheme:dark;
}
*{box-sizing:border-box;margin:0;padding:0}
html,body{min-height:100%;scroll-behavior:smooth}
body{
  font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial;
  color:var(--text);
  background:var(--bg);
  overflow-y:auto;
  animation:bgFade 1s ease forwards;
}
@keyframes bgFade{from{background:#020409;opacity:.3;}to{background:var(--bg);opacity:1;}}

/* 🌈 Aurora background */
.bg{position:fixed;inset:0;z-index:-2;
  background:
    radial-gradient(60vw 60vh at 80% -10%, rgba(157,125,255,.18), transparent 60%),
    radial-gradient(50vw 50vh at 10% 110%, rgba(110,168,254,.18), transparent 60%),
    linear-gradient(180deg, #070B14 0%, #0E1425 100%);
  animation: aurora 18s ease-in-out infinite alternate;
  background-size:200% 200%;
}
@keyframes aurora{0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}

/* ✨ Particles */
.particle{position:fixed;width:4px;height:4px;background:rgba(255,255,255,.2);
  border-radius:50%;animation:float 10s linear infinite;}
@keyframes float{from{transform:translateY(100vh) scale(0.5);opacity:0.4;}to{transform:translateY(-10vh) scale(1);opacity:0;}}

/* 🔝 Top bar */
.topbar{position:fixed;top:0;left:0;right:0;height:56px;
  display:flex;align-items:center;justify-content:space-between;
  padding:0 16px;background:rgba(10,16,27,.6);backdrop-filter:blur(8px);
  border-bottom:1px solid rgba(255,255,255,.08);z-index:10;animation:fadeDown .8s ease .2s both;}
@keyframes fadeDown{from{opacity:0;transform:translateY(-20px);}to{opacity:1;transform:translateY(0);}}
.topbar a{color:var(--text);text-decoration:none;opacity:.9}
.brand{font-weight:800;display:flex;align-items:center;gap:8px}
.brand .dot{width:10px;height:10px;border-radius:50%;
  background:linear-gradient(90deg,var(--accent),var(--accent2));box-shadow:0 0 12px var(--accent)}

/* 🎬 Smooth Card Entrance */
.container{max-width:520px;margin:100px auto 80px;padding:26px 22px;
  background:rgba(17,23,35,.85);border:1px solid rgba(255,255,255,.08);
  border-radius:22px;box-shadow:0 0 40px rgba(110,168,254,.15);
  backdrop-filter:blur(14px);opacity:0;transform:translateY(50px) scale(.97);
  animation:cardUp 1s ease-out .4s forwards;}
@keyframes cardUp{0%{opacity:0;transform:translateY(50px) scale(.97);}
60%{opacity:1;transform:translateY(-4px) scale(1.02);}100%{transform:translateY(0) scale(1);opacity:1;}}
h2{text-align:center;margin-bottom:18px;font-size:26px;}

/* ⚠️ & ✅ Alerts */
.alert,.success{padding:10px;border-radius:10px;margin-bottom:12px;text-align:center;animation:fadeIn .4s ease;}
.alert{background:rgba(255,107,107,.12);border:1px solid rgba(255,107,107,.55);color:#ffd1d1;}
.success{background:rgba(66,239,154,.12);border:1px solid rgba(66,239,154,.55);color:#b7ffce;}
@keyframes fadeIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:none}}

/* Inputs + Floating Labels */
form{display:flex;flex-direction:column;gap:14px}
.fg{position:relative}
input{width:100%;height:52px;line-height:52px;padding:0 44px 0 14px;border-radius:12px;
  border:1px solid rgba(255,255,255,.16);background:rgba(255,255,255,.06);color:var(--text);
  transition:border .2s,box-shadow .3s,background .2s;}
input:focus{border-color:var(--accent);box-shadow:0 0 25px rgba(110,168,254,.3);background:rgba(255,255,255,.09);}
label.floating{position:absolute;left:14px;top:12px;color:var(--muted);font-size:14px;pointer-events:none;transition:all .15s ease;opacity:.9;}
input:not(:placeholder-shown)+label.floating,input:focus+label.floating{top:-9px;left:10px;font-size:12px;background:rgba(17,23,35,.95);padding:0 5px;border-radius:6px;opacity:.85;}

/* Eye toggle */
.toggle{position:absolute;right:12px;top:26px;transform:translateY(-50%);
  width:32px;height:32px;border:none;background:rgba(255,255,255,.07);border-radius:8px;display:grid;place-items:center;cursor:pointer;}
.toggle:hover{background:rgba(255,255,255,.12);}
.eye{width:18px;height:18px}

/* Password Strength Meter */
.meter{height:8px;border-radius:8px;background:rgba(255,255,255,.1);overflow:hidden;margin-top:8px}
.meter b{display:block;height:100%;width:0%;background:linear-gradient(90deg,#ff6b6b,#ffd166,#42ef9a);transition:width .25s ease}
.meter-label{font-size:12px;color:var(--muted);margin-top:4px}

/* Button */
.btn{margin-top:10px;padding:12px 16px;border:none;border-radius:12px;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  color:#0c1220;font-weight:700;cursor:pointer;
  box-shadow:0 10px 24px rgba(110,168,254,.35);
  transition:transform .15s,box-shadow .2s,opacity .2s;}
.btn:hover{transform:translateY(-1px);box-shadow:0 14px 28px rgba(110,168,254,.45);}
.btn:disabled{opacity:.6;cursor:not-allowed}

/* Chips + Footer */
.chips{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px;justify-content:center}
.chip{border:1px solid rgba(255,255,255,.16);color:var(--muted);
  padding:6px 10px;border-radius:999px;font-size:13px;backdrop-filter:blur(6px);background:rgba(255,255,255,.04)}
.footer{text-align:center;color:#8fa3c8;padding:20px;font-size:13px;opacity:.85;margin-bottom:30px;}
</style>
</head>
<body>
<div class="bg"></div>

<script>
for(let i=0;i<30;i++){
  const p=document.createElement('div');
  p.className='particle';
  p.style.left=Math.random()*100+'vw';
  p.style.animationDuration=5+Math.random()*10+'s';
  p.style.animationDelay=Math.random()*10+'s';
  document.body.appendChild(p);
}
</script>

<div class="topbar">
  <div class="brand"><span class="dot"></span>KeyDash</div>
  <a href="index.php">← Home</a>
</div>

<div class="container">
  <h2>Create Account</h2>

  <?php if($err): ?><div class="alert"><?= htmlspecialchars($err) ?></div><?php endif; ?>
  <?php if($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <form method="post" id="regForm" autocomplete="off" novalidate>
    <div class="fg">
      <input id="name" name="name" required placeholder=" " value="<?= htmlspecialchars($_POST['name']??'') ?>">
      <label for="name" class="floating">Full Name</label>
    </div>

    <div class="fg">
      <input id="email" name="email" type="email" required placeholder=" " value="<?= htmlspecialchars($_POST['email']??'') ?>">
      <label for="email" class="floating">Email Address</label>
    </div>

    <div class="fg">
      <input id="password" name="password" type="password" required placeholder=" ">
      <label for="password" class="floating">Password</label>
      <button type="button" class="toggle" id="togglePw">
        <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="#cfe1ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/>
        </svg>
      </button>
      <div class="meter"><b id="meterBar"></b></div>
      <div class="meter-label" id="meterText">Strength: —</div>
    </div>

    <button class="btn" id="btnSubmit" type="submit">Register</button>

    <div class="chips">
      <span class="chip">Save WPM & accuracy</span>
      <span class="chip">Compete on leaderboard</span>
      <span class="chip">Custom durations</span>
      <span class="chip">Auto-next sentences</span>
    </div>

    <p style="text-align:center;color:var(--muted);margin-top:10px">
      Have an account? <a href="login.php" style="color:#fff">Login</a>
    </p>
  </form>
</div>

<div class="footer">Developed By Rupam</div>

<script>
/* Toggle Password */
const pw=document.getElementById('password');
const btn=document.getElementById('togglePw');
let shown=false;
btn.addEventListener('click',()=>{shown=!shown;pw.type=shown?'text':'password';
btn.querySelector('.eye').setAttribute('stroke',shown?'#42ef9a':'#cfe1ff');});

/* Strength Meter */
const bar=document.getElementById('meterBar');
const txt=document.getElementById('meterText');
function score(s){let n=0;if(s.length>=6)n+=20;if(/[A-Z]/.test(s))n+=20;if(/[a-z]/.test(s))n+=20;if(/[0-9]/.test(s))n+=20;if(/[^A-Za-z0-9]/.test(s))n+=20;return Math.min(n,100);}
function label(v){return v<40?"Weak":v<70?"Okay":"Strong";}
pw.addEventListener('input',()=>{const sc=score(pw.value||"");bar.style.width=sc+"%";txt.textContent="Strength: "+label(sc);});
</script>
</body>
</html>
