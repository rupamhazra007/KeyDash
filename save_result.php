<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');

if($_SERVER['REQUEST_METHOD']!=='POST'){ http_response_code(405); echo json_encode(['ok'=>false,'error'=>'Method not allowed']); exit; }
if(!current_user()){ http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); exit; }

$raw = file_get_contents('php://input');
$data = json_decode($raw,true);
$wpm = $data['wpm'] ?? null;
$acc = $data['accuracy'] ?? null;
$dur = $data['duration'] ?? 60;
$qln = $data['quote_len'] ?? 0;

if(!is_numeric($wpm) || !is_numeric($acc)){ http_response_code(422); echo json_encode(['ok'=>false,'error'=>'Invalid payload']); exit; }

$st = $pdo->prepare("INSERT INTO results(user_id,wpm,accuracy,duration_seconds,quote_len) VALUES(?,?,?,?,?)");
$st->execute([ current_user()['id'], $wpm, $acc, $dur, $qln ]);

echo json_encode(['ok'=>true]);
