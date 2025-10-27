<?php
/* stats.php
 * NOTE: คืนจำนวนกระทงทั้งหมด (สำหรับหน้าแรก)
 */
require __DIR__.'/db.php';
$total = (int) db()->query("SELECT COUNT(*) FROM krathongs")->fetchColumn();
json_out(['total'=>$total]);
