<?php
// sidebar.php
$current = basename($_SERVER['PHP_SELF']);
function activeLink($file) {
  global $current;
  return $current === $file ? 'background:#2563eb;color:#fff;font-weight:bold;' : '';
}
?>
<div style="width: 250px; background-color: #1f2937; height: 100vh; position: fixed; right: 0; top: 0; color: white; font-family: 'Tajawal', sans-serif; z-index:1000;">
  <div style="padding: 20px; font-size: 1.2rem; font-weight: bold; border-bottom: 1px solid #374151;">
    🗂️ لوحة التحكم
  </div>
  <ul style="list-style: none; padding: 0; margin: 0;">
    <li><a href='admin_dashboard.php' style='display: block; padding: 15px 20px; color: white; text-decoration: none;<?=activeLink("admin_dashboard.php")?>' onmouseover="this.style.background='#374151'" onmouseout="this.style.background='<?=activeLink("admin_dashboard.php")?"#2563eb":""?>'">🛡️ أدمن</a></li>
    <li><a href='index.php' style='display: block; padding: 15px 20px; color: white; text-decoration: none;<?=activeLink("index.php")?>' onmouseover="this.style.background='#374151'" onmouseout="this.style.background='<?=activeLink("index.php")?"#2563eb":""?>'">🏠 الرئيسية</a></li>
    <li><a href='uploads_list.php' style='display: block; padding: 15px 20px; color: white; text-decoration: none;<?=activeLink("uploads_list.php")?>' onmouseover="this.style.background='#374151'" onmouseout="this.style.background='<?=activeLink("uploads_list.php")?"#2563eb":""?>'">📋 قائمة الملفات</a></li>
    <li><a href='upload_folder.php' style='display: block; padding: 15px 20px; color: white; text-decoration: none;<?=activeLink("upload_folder.php")?>' onmouseover="this.style.background='#374151'" onmouseout="this.style.background='<?=activeLink("upload_folder.php")?"#2563eb":""?>'">📁 رفع مجلد</a></li>
    <li><a href='upload.php' style='display: block; padding: 15px 20px; color: white; text-decoration: none;<?=activeLink("upload.php")?>' onmouseover="this.style.background='#374151'" onmouseout="this.style.background='<?=activeLink("upload.php")?"#2563eb":""?>'">🖼️ رفع ملف</a></li>
    <li><a href='view_files.php' style='display: block; padding: 15px 20px; color: white; text-decoration: none;<?=activeLink("view_files.php")?>' onmouseover="this.style.background='#374151'" onmouseout="this.style.background='<?=activeLink("view_files.php")?"#2563eb":""?>'">📂 عرض الملفات</a></li>
    <li><a href='gallery.php' style='display: block; padding: 15px 20px; color: white; text-decoration: none;<?=activeLink("gallery.php")?>' onmouseover="this.style.background='#374151'" onmouseout="this.style.background='<?=activeLink("gallery.php")?"#2563eb":""?>'">🖼️ معرض الصور</a></li>
    <li><a href='fetch_from_email.php' style='display: block; padding: 15px 20px; color: white; text-decoration: none;<?=activeLink("fetch_from_email.php")?>' onmouseover="this.style.background='#374151'" onmouseout="this.style.background='<?=activeLink("fetch_from_email.php")?"#2563eb":""?>'">✉️ جلب من البريد</a></li>
    <li><a href='imap_test.php' style='display: block; padding: 15px 20px; color: white; text-decoration: none;<?=activeLink("imap_test.php")?>' onmouseover="this.style.background='#374151'" onmouseout="this.style.background='<?=activeLink("imap_test.php")?"#2563eb":""?>'">🛠️ اختبار IMAP</a></li>
    <li><a href='phpinfo.php' style='display: block; padding: 15px 20px; color: white; text-decoration: none;<?=activeLink("phpinfo.php")?>' onmouseover="this.style.background='#374151'" onmouseout="this.style.background='<?=activeLink("phpinfo.php")?"#2563eb":""?>'">⚙️ PHP Info</a></li>
  </ul>
</div>
