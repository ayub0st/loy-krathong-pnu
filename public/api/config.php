<?php
/* config.php
 * NOTE: ตั้งค่าการเชื่อมต่อฐานข้อมูล MySQL/MariaDB
 *  - db_dsn: host, dbname และ charset (ควรใช้ utf8mb4 เสมอ)
 *  - db_user/db_pass: เปลี่ยนรหัสผ่านให้ปลอดภัย
 */
return [
  'db_dsn'  => 'mysql:host=127.0.0.1;dbname=loy_krathong;charset=utf8mb4',
  'db_user' => 'loy_user',
  'db_pass' => 'CHANGE_ME_STRONG_PASSWORD',
];
