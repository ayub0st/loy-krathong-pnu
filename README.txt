# Loy Krathong Online (PHP + MySQL)
คู่มือสั้น:
1) สร้างฐานข้อมูลและตาราง (รันใน MySQL):
   CREATE DATABASE IF NOT EXISTS loy_krathong CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE loy_krathong;
   CREATE TABLE krathongs (
     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(80) NOT NULL,
     wish TEXT NOT NULL,
     design VARCHAR(64) NOT NULL,
     ip VARCHAR(45) NULL,
     ua VARCHAR(255) NULL,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     INDEX (created_at), INDEX (design)
   );

2) แก้ไฟล์: public/api/config.php (db_user, db_pass)

3) วางโฟลเดอร์นี้ไว้ที่ DocumentRoot ของเว็บเซิร์ฟเวอร์ เช่น /var/www/loy-krathong-php
   - DocumentRoot ควรชี้ไปที่โฟลเดอร์ 'public'

4) เปิดเบราว์เซอร์เข้า: http://YOUR_HOST/  แล้วทดสอบ


loy-krathong-php/
├─ public/
│  ├─ index.html         ← หน้าแรก (โชว์จำนวนกระทง + ล่าสุด)
│  ├─ create.html        ← หน้ากรอกชื่อ/คำอธิษฐาน + เลือกกระทง
│  ├─ float.html         ← หน้าลอยกระทง + ปุ่มแชร์
│  ├─ styles.css         ← CSS (มีคอมเมนต์ตำแหน่งเปลี่ยนพื้นหลัง)
│  ├─ app.js             ← Frontend JS (มีคอมเมนต์จุดแก้ข้อความ/ลิงก์/limit)
│  ├─ assets/
│  │  ├─ bg.jpg          ← ภาพพื้นหลัง (แก้ไฟล์นี้ได้เลย)
│  │  ├─ krathong1.svg   ← ภาพกระทงแบบที่ 1
│  │  ├─ krathong2.svg   ← ภาพกระทงแบบที่ 2
│  │  └─ krathong3.svg   ← ภาพกระทงแบบที่ 3
│  └─ api/
│     ├─ config.php      ← ตั้งค่า MySQL (user/pass/host)
│     ├─ db.php          ← เชื่อม PDO + helper ตอบ JSON
│     ├─ submit.php      ← POST บันทึกกระทง (มี rate-limit)
│     ├─ stats.php       ← GET จำนวนทั้งหมด
│     └─ recent.php      ← GET รายการล่าสุด
└─ README.txt            ← ขั้นตอนติดตั้งแบบย่อ + SQL schema
