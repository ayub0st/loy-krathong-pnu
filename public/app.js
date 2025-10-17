/* ================= Loy Krathong Frontend Script =================
 * NOTE จุดที่แก้ไขบ่อย:
 *  - ชื่อ/ข้อความ: index.html, create.html, float.html (แก้ได้ตรงๆในไฟล์)
 *  - จำนวน 'ล่าสุด' ให้แสดง: ปรับพารามิเตอร์ limit ด้านล่าง
 *  - ข้อความเวลแชร์: ฟังก์ชัน setupShareButtons()
 *  - ตำแหน่ง API: const API = origin + '/api' (ถ้าย้าย path ให้แก้ตรงนี้)
 */
// NOTE: เรียก API แบบ relative จากหน้าในโฟลเดอร์ public
const API = "api";

async function getJSON(url) {
  const r = await fetch(url, {cache:'no-store'});
  if (!r.ok) throw new Error('HTTP '+r.status);
  return r.json();
}
async function postJSON(url, data) {
  const r = await fetch(url, {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify(data)
  });
  if (!r.ok) {
    const t = await r.text().catch(()=>'');
    throw new Error('HTTP '+r.status+' '+t);
  }
  return r.json();
}

// Escape ข้อความก่อนแสดงผลเพื่อกัน XSS
function esc(s){return String(s).replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]))}

document.addEventListener('DOMContentLoaded', async () => {

  // ----- หน้า index: โหลดสถิติ + ล่าสุด -----
  const counterEl = document.getElementById('counter');
  if (counterEl) {
    try {
      const s = await getJSON(`${API}/stats.php`);
      counterEl.textContent = s.total ?? 0;
    } catch(e){ counterEl.textContent = '—'; }

    try {
      const rec = await getJSON(`${API}/recent.php?limit=10`); // NOTE: เปลี่ยน 10 ได้
      const ul = document.getElementById('recentList');
      rec.items?.forEach(it => {
        const li = document.createElement('li');
        li.innerHTML = `<strong>${esc(it.name)}</strong> — ${esc(it.wish)} <small>(${new Date(it.created_at).toLocaleString()})</small>`;
        ul.appendChild(li);
      });
    } catch(e){ /* แสดงเงียบๆ */ }
  }

  // ----- หน้า create: submit แบบ AJAX แล้วไป float.html -----
  const form = document.getElementById('formKrathong');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const name = document.getElementById('name').value.trim();
      const wish = document.getElementById('wish').value.trim();
      const design = [...document.querySelectorAll('input[name=design]')].find(i=>i.checked).value;

      if (!name || !wish) { alert('กรุณากรอกชื่อและคำอธิษฐาน'); return; }

      try {
        const res = await postJSON(`${API}/submit.php`, {name, wish, design});
        if (!res.ok) throw new Error(res.error||'unknown');
      } catch(e){
        alert('ส่งไม่สำเร็จ: '+ e.message);
        return;
      }

      // ส่งพารามิเตอร์ไป float.html เพื่อโชว์
      const q = new URLSearchParams({name, wish, design});
      location.href = `float.html?${q.toString()}`;
    });
  }

  // ----- หน้า float: โหลดข้อมูลจาก query และตั้งลิงก์แชร์ -----
  const img = document.getElementById('krathongImg');
  const wishText = document.getElementById('wishText');
  if (img && wishText) {
    const p = new URLSearchParams(location.search);
    const name = p.get('name') || '';
    const wish = p.get('wish') || '';
    const design = p.get('design') || 'assets/krathong1.svg';
    img.src = design;

    wishText.textContent = `${name} : ${wish}`; // NOTE: ปรับรูปแบบได้ตามต้องการ

    setupShareButtons(name, wish);
  }
});

function setupShareButtons(name, wish){
  // NOTE: ปรับข้อความแชร์ที่นี่
  const pageUrl = location.href.split('?')[0];
  const shareText = `${name} ลอยกระทงออนไลน์: ${wish}`;

  const line = document.getElementById('shareLine');
  const fb   = document.getElementById('shareFB');
  if (line) line.href = `https://line.me/R/msg/text/?${encodeURIComponent(shareText + ' ' + pageUrl)}`;
  if (fb)   fb.href   = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`;
}
