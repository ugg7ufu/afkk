<?php
$pdo = new PDO("mysql:host=localhost;dbname=my_app_data;charset=utf8mb4", "root", "");
// البحث والتصفية
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type_filter = isset($_GET['type']) ? trim($_GET['type']) : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'uploaded_at';
$dir = isset($_GET['dir']) && $_GET['dir'] === 'asc' ? 'ASC' : 'DESC';
$allowedOrder = ['file_name','file_type','file_size','uploaded_at'];
if (!in_array($order, $allowedOrder)) $order = 'uploaded_at';
$sql = "SELECT * FROM uploads WHERE 1";
$params = [];
if ($search) {
  $sql .= " AND (file_name LIKE :s OR file_type LIKE :s)";
  $params[':s'] = "%$search%";
}
if ($type_filter) {
  $sql .= " AND file_type = :t";
  $params[':t'] = $type_filter;
}
$sql .= " ORDER BY $order $dir";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
// أنواع الملفات المتوفرة
$types = $pdo->query("SELECT DISTINCT file_type FROM uploads ORDER BY file_type")->fetchAll(PDO::FETCH_COLUMN);
// إحصائيات
$total = $pdo->query("SELECT COUNT(*) FROM uploads")->fetchColumn();
$totalSize = $pdo->query("SELECT SUM(file_size) FROM uploads")->fetchColumn();
$typeStats = $pdo->query("SELECT file_type, COUNT(*) as cnt FROM uploads GROUP BY file_type")->fetchAll(PDO::FETCH_KEY_PAIR);
function humanFileSize($size) {
  if ($size < 1024) return $size . ' بايت';
  if ($size < 1024*1024) return round($size/1024, 2) . ' ك.ب';
  if ($size < 1024*1024*1024) return round($size/1024/1024, 2) . ' م.ب';
  return round($size/1024/1024/1024, 2) . ' ج.ب';
}
// حذف ملف
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $pdo->query("DELETE FROM uploads WHERE id=$id");
  header("Location: uploads_list.php?deleted=1");
  exit;
}
// تنبيه عند إضافة مرفق جديد أو حذف
$alert = '';
if (isset($_GET['added'])) $alert = '<div class="alert alert-success">✔️ تم إضافة مرفق جديد!</div>';
if (isset($_GET['deleted'])) $alert = '<div class="alert alert-danger">❌ تم حذف المرفق بنجاح.</div>';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📂 الملفات المرفوعة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body { background: #f9f9f9; font-family: 'Tajawal', sans-serif; }
    .card { border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.08); margin-bottom: 20px; }
    .file-preview { height: 150px; object-fit: cover; }
    .grid-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
    @media (max-width: 900px) {
      .grid-container { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
      .grid-container { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div style="margin-right:270px; min-height:100vh;">
    <div class="container py-4">
      <h2 class="text-center mb-4">📁 عرض الملفات المرفوعة</h2>
      <div class="grid-container">
        <?php foreach ($files as $file): 
          $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)$/i', $file['file_name']);
          $url = "uploads/" . $file['file_name'];
        ?>
          <div class="card">
            <?php if ($isImage): ?>
              <img src="<?= htmlspecialchars($url) ?>" class="card-img-top file-preview" alt="Preview">
            <?php else: ?>
              <div class="file-preview d-flex justify-content-center align-items-center bg-light">
                📄 <?= pathinfo($file['file_name'], PATHINFO_EXTENSION) ?>
              </div>
            <?php endif; ?>
            <div class="card-body">
              <h6 class="card-title"><?= htmlspecialchars(basename($file['file_name'])) ?></h6>
              <p class="text-muted small mb-2">الحجم: <?= round($file['file_size'] / 1024, 2) ?> KB</p>
              <a href="<?= htmlspecialchars($url) ?>" target="_blank" class="btn btn-sm btn-primary w-100">فتح / تحميل</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</body>
</html>
