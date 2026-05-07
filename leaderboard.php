<?php require_once __DIR__ . '/db.php';
$rows = $pdo->query("
  SELECT u.name, r.wpm, r.accuracy, r.created_at
  FROM results r JOIN users u ON u.id=r.user_id
  ORDER BY r.wpm DESC, r.accuracy DESC, r.created_at DESC
  LIMIT 20
")->fetchAll();
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Leaderboard · KeyDash</title>
<style>
body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto;background:#0b1220;color:#e7ecf7}
a{color:#e7ecf7;text-decoration:none}
.wrap{max-width:900px;margin:24px auto;padding:0 16px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.12);border-radius:16px;padding:18px}
table{width:100%;border-collapse:collapse}
th,td{padding:10px 12px;border-bottom:1px solid rgba(255,255,255,.12)}
th{text-align:left;color:#9fb0d0}
.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
</style></head><body>
<div class="wrap">
  <div class="top"><a href="index.php">← Home</a><b>KeyDash</b></div>
  <div class="card">
    <h2 style="margin-top:0">Leaderboard (Top 20)</h2>
    <table>
      <thead><tr><th>#</th><th>Name</th><th>WPM</th><th>Accuracy</th><th>When</th></tr></thead>
      <tbody>
      <?php foreach($rows as $i=>$r): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= htmlspecialchars($r['name']) ?></td>
          <td><?= number_format($r['wpm'],2) ?></td>
          <td><?= number_format($r['accuracy'],2) ?>%</td>
          <td><?= htmlspecialchars($r['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body></html>
