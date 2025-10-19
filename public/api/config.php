<?php
/* api/config.php
 * รองรับทั้ง MySQL และ MariaDB (MAMP/Server)
 */

// ตรวจสอบว่าเป็น local (MAMP) หรือ production server
$isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '::1']);

if ($isLocal) {
    // การตั้งค่าสำหรับ MAMP บน macOS
    return [
        'db_dsn'  => 'mysql:host=localhost;port=8889;dbname=loy_krathong;charset=utf8mb4',
        'db_user' => 'root',
        'db_pass' => 'root', // MAMP default
    ];
} else {
    // การตั้งค่าสำหรับ Production Server
    return [
        'db_dsn'  => 'mysql:host=localhost;dbname=loy_krathong;charset=utf8mb4',
        'db_user' => 'loy_user',
        'db_pass' => 'YOUR_STRONG_PASSWORD_HERE',
    ];
}

/* ============================================
 * SQL สำหรับสร้างฐานข้อมูล
 * ============================================
 * คัดลอกคำสั่งด้านล่างนี้ไปรันใน phpMyAdmin
 * (ใน MAMP เปิดที่ http://localhost:8888/phpMyAdmin/)
 * http://localhost/loy-krathong-php/public/index.html
 */

/*

-- 1. สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS loy_krathong 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE loy_krathong;

-- 2. สร้างตารางเก็บข้อมูลกระทง
CREATE TABLE IF NOT EXISTS krathongs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL COMMENT 'ชื่อผู้ลอย',
  wish TEXT NOT NULL COMMENT 'คำอธิษฐาน',
  design VARCHAR(255) NOT NULL COMMENT 'รูปแบบกระทง',
  ip VARCHAR(50) DEFAULT NULL COMMENT 'IP Address',
  ua TEXT DEFAULT NULL COMMENT 'User Agent',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'เวลาที่ลอย',
  
  INDEX idx_created (created_at),
  INDEX idx_ip (ip)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_unicode_ci
  COMMENT='ตารางเก็บข้อมูลกระทงที่ลอย';

-- 3. (Optional) สร้าง user สำหรับ production
-- ใช้เฉพาะเมื่อใช้งานบน server จริง
CREATE USER IF NOT EXISTS 'loy_user'@'localhost' 
IDENTIFIED BY 'YOUR_STRONG_PASSWORD_HERE';

GRANT SELECT, INSERT, UPDATE, DELETE 
ON loy_krathong.* 
TO 'loy_user'@'localhost';

FLUSH PRIVILEGES;

-- 4. ทดสอบด้วยการ insert ข้อมูลตัวอย่าง
INSERT INTO krathongs (name, wish, design) VALUES
('สมชาย', 'ขอให้ทุกคนมีความสุข', 'assets/krathong1.svg'),
('สมหญิง', 'ขอให้เรียนเก่ง สอบติด', 'assets/krathong2.svg'),
('ทดสอบ', 'ขอให้รวยๆ', 'assets/krathong3.svg');

-- 5. ตรวจสอบข้อมูล
SELECT * FROM krathongs ORDER BY id DESC LIMIT 10;

*/