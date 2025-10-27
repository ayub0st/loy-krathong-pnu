<?php
/* submit.php (POST: name, wish, design)
 * NOTE: บันทึกข้อมูลกระทงลงฐานข้อมูล พร้อม rate limit แบบง่าย
 * จุดที่แก้ไขบ่อย:
 *  - จำกัดความยาวชื่อ/คำอธิษฐาน
 *  - ปรับนโยบาย rate limit
 */
require __DIR__.'/db.php';

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) { $input = $_POST; }

$name   = trim($input['name']   ?? '');
$wish   = trim($input['wish']   ?? '');
$design = trim($input['design'] ?? '');

if ($name==='' || $wish==='' || $design==='') json_out(['ok'=>false,'error'=>'INVALID_INPUT'], 400);

// จำกัดความยาว
if (mb_strlen($name) > 80)   json_out(['ok'=>false,'error'=>'NAME_TOO_LONG'], 400);
if (mb_strlen($wish) > 1000) json_out(['ok'=>false,'error'=>'WISH_TOO_LONG'], 400);

// Rate limit: IP นี้ไม่เกิน 5 ครั้ง/นาที
$ip = $_SERVER['REMOTE_ADDR'] ?? null;
$ua = $_SERVER['HTTP_USER_AGENT'] ?? null;

$stmt = db()->prepare("SELECT COUNT(*) AS c FROM krathongs WHERE ip = ? AND created_at > (NOW() - INTERVAL 1 MINUTE)");
$stmt->execute([$ip]);
$row = $stmt->fetch();
if ((int)$row['c'] > 5) json_out(['ok'=>false,'error'=>'RATE_LIMIT'], 429);

// Insert
$stmt = db()->prepare("INSERT INTO krathongs (name, wish, design, ip, ua) VALUES (?,?,?,?,?)");
$stmt->execute([$name, $wish, $design, $ip, $ua]);

json_out(['ok'=>true,'id'=>db()->lastInsertId()]);
