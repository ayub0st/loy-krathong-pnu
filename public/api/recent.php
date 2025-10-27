<?php
/* recent.php (GET ?limit=10)
 * NOTE: คืนรายการกระทงล่าสุด พร้อมชื่อ/คำอธิษฐาน/เวลา
 */
require __DIR__.'/db.php';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$limit = max(1, min(50, $limit));
$stmt = db()->prepare("SELECT id,name,wish,design,created_at FROM krathongs ORDER BY id DESC LIMIT $limit");
$stmt->execute();
json_out(['items'=>$stmt->fetchAll()]);
