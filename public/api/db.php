<?php
/* db.php
 * NOTE: ยูทิลิตี้เชื่อมต่อฐานข้อมูลด้วย PDO และตัวช่วยตอบ JSON
 */
$config = require __DIR__.'/config.php';

function db() {
  static $pdo;
  global $config;
  if (!$pdo) {
    $pdo = new PDO(
      $config['db_dsn'], $config['db_user'], $config['db_pass'],
      [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
    );
    // บังคับโหมด strict เพื่อข้อมูลสะอาด
    $pdo->exec("SET SESSION sql_mode='STRICT_ALL_TABLES'");
  }
  return $pdo;
}
function json_out($data, $code=200) {
  http_response_code($code);
  header('Content-Type: application/json; charset=utf-8');
  header('Cache-Control: no-store');
  echo json_encode($data, JSON_UNESCAPED_UNICODE);
  exit;
}
