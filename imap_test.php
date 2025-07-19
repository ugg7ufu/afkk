<?php
$status = null;
if (function_exists('imap_open')) {
    $status = ['success', '✅ دالة imap_open مفعّلة وتعمل بنجاح.'];
} else {
    $status = ['danger', '❌ دالة imap_open غير مفعّلة. تحتاج إلى تفعيل IMAP في PHP.'];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>اختبار IMAP</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div style="margin-right: 270px; padding: 40px;">
  <h2>🛠️ اختبار دعم IMAP في PHP</h2>
  <div class="alert alert-<?= $status[0] ?> mt-4" style="font-size:1.2em;">
    <?= $status[1] ?>
  </div>
</div>
</body>
</html>
