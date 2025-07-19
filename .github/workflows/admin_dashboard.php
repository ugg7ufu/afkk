<?php
$baseDir = 'uploads/';
$imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
$folders = array_filter(glob($baseDir . '*'), 'is_dir');
$selectedFolder = isset($_GET['folder']) ? basename($_GET['folder']) : '';
$selectedFolderPath = realpath($baseDir . $selectedFolder);

// تأكيد أن المجلد موجود فعلاً
if ($selectedFolder && (!is_dir($baseDir . $selectedFolder) || !in_array($baseDir . $selectedFolder, $folders))) {
  $selectedFolder = '';
  $selectedFolderPath = '';
}

// دوال الإحصائيات
function countFiles($dir) {
  $count = 0;
  foreach (glob($dir . '*') as $item) {
    if (is_file($item)) $count++;
    if (is_dir($item)) $count += countFiles($item . '/');
  }
  return $count;
}
function countImages($dir, $exts) {
  $count = 0;
  foreach (glob($dir . '*') as $item) {
    if (is_file($item) && in_array(strtolower(pathinfo($item, PATHINFO_EXTENSION)), $exts)) $count++;
    if (is_dir($item)) $count += countImages($item . '/', $exts);
  }
  return $count;
}
function totalSize($dir) {
  $size = 0;
  foreach (glob($dir . '*') as $item) {
    if (is_file($item)) $size += filesize($item);
    if (is_dir($item)) $size += totalSize($item . '/');
  }
  return $size;
}
function humanFileSize($size) {
  if ($size < 1024) return $size . ' بايت';
  if ($size < 1024 * 1024) return round($size / 1024, 2) . ' ك.ب';
  if ($size < 1024 * 1024 * 1024) return round($size / 1024 / 1024, 2) . ' م.ب';
  return round($size / 1024 / 1024 / 1024, 2) . ' ج.ب';
}

// إنشاء مجلد جديد
$msg = '';
if (isset($_POST['new_folder']) && !empty($_POST['folder_name'])) {
  $folderName = trim($_POST['folder_name']);
  $folderName = preg_replace('/[^a-zA-Z0-9-_]/u', '_', $folderName);
  if ($folderName === '') {
    $msg = "<div class='alert alert-danger'>❌ اسم المجلد غير صالح.</div>";
  } else {
    $newFolderPath = $baseDir . $folderName;
    if (!is_dir($newFolderPath)) {
      mkdir($newFolderPath, 0777, true);
      $msg = "<div class='alert alert-success'>✔️ تم إنشاء المجلد: $folderName</div>";
      $folders = array_filter(glob($baseDir . '*'), 'is_dir');
    } else {
      $msg = "<div class='alert alert-warning'>⚠️ المجلد موجود بالفعل!</div>";
    }
  }
}

// رفع ملف
if ($selectedFolder && isset($_POST['upload_btn']) && isset($_FILES['upload_file'])) {
  $uploadDir = $baseDir . $selectedFolder . '/';
  $uploadFile = $uploadDir . basename($_FILES['upload_file']['name']);
  if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $uploadFile)) {
    $msg .= "<div class='alert alert-success mt-2'>✔️ تم رفع الملف بنجاح.</div>";
  } else {
    $msg .= "<div class='alert alert-danger mt-2'>❌ فشل في رفع الملف.</div>";
  }
}

// حذف ملف
if ($selectedFolder && isset($_GET['delete'])) {
  $delFile = basename($_GET['delete']);
  $delPath = realpath($baseDir . $selectedFolder . '/' . $delFile);
  if ($delPath && strpos($delPath, $selectedFolderPath) === 0 && is_file($delPath)) {
    unlink($delPath);
    echo "<script>alert('تم حذف الملف بنجاح');window.location='admin_dashboard.php?folder=" . urlencode($selectedFolder) . "';</script>";
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>لوحة تحكم الأدمن</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" />
  <style>
    .folder-link { font-size: 1.1em; font-weight: bold; margin: 0 10px; }
    .folder-link.active { color: #fff; background: #2563eb; border-radius: 6px; padding: 2px 10px; }
    .gallery-img { max-width: 120px; max-height: 120px; border-radius: 8px; box-shadow: 0 2px 8px #0001; margin-bottom: 8px; }
    .gallery-item { text-align: center; margin-bottom: 30px; }
    .file-icon { font-size: 2.5em; color: #888; }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div style="margin-right: 270px; padding: 30px;">
  <h2 class="mb-4">🛡️ لوحة تحكم الأدمن</h2>

  <div class="row mb-4">
    <div class="col-md-3"><div class="card text-center bg-primary text-white mb-2"><div class="card-body">عدد الملفات<br><b><?= countFiles($baseDir) ?></b></div></div></div>
    <div class="col-md-3"><div class="card text-center bg-success text-white mb-2"><div class="card-body">عدد الصور<br><b><?= countImages($baseDir, $imageExts) ?></b></div></div></div>
    <div class="col-md-3"><div class="card text-center bg-info text-white mb-2"><div class="card-body">الحجم الكلي<br><b><?= humanFileSize(totalSize($baseDir)) ?></b></div></div></div>
    <div class="col-md-3"><div class="card text-center bg-warning mb-2"><div class="card-body">عدد المجلدات<br><b><?= count($folders) ?></b></div></div></div>
  </div>

  <?= $msg ?>

  <form method="post" class="mb-4 d-flex gap-2">
    <input type="text" name="folder_name" class="form-control" placeholder="اسم المجلد الجديد" required>
    <button type="submit" name="new_folder" class="btn btn-primary">إنشاء مجلد</button>
  </form>

  <div class="mb-4">
    <b>المجلدات:</b>
    <?php foreach ($folders as $folder): $f = basename($folder); ?>
      <a href="?folder=<?= urlencode($f) ?>" class="folder-link<?= $selectedFolder === $f ? ' active' : '' ?>">📁 <?= htmlspecialchars($f) ?></a>
    <?php endforeach; ?>
  </div>

  <?php if ($selectedFolder): ?>
    <form method="post" enctype="multipart/form-data" class="mb-4">
      <div class="input-group">
        <input type="file" name="upload_file" class="form-control" required>
        <button class="btn btn-secondary" name="upload_btn">رفع</button>
      </div>
    </form>

    <div class="row">
      <?php
      $files = scandir($baseDir . $selectedFolder);
      $hasFiles = false;
      foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $filePath = $baseDir . $selectedFolder . '/' . $file;
        if (!is_file($filePath)) continue;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $hasFiles = true;
        echo '<div class="col-md-3 gallery-item">';
        if (in_array($ext, $imageExts)) {
          echo '<a href="' . htmlspecialchars($filePath) . '" data-lightbox="gallery">';
          echo '<img src="' . htmlspecialchars($filePath) . '" class="gallery-img" alt="">';
          echo '</a>';
        } else {
          echo '<div class="file-icon">📄</div>';
        }
        echo '<div class="small mt-2">' . htmlspecialchars($file) . '</div>';
        echo '<div class="mt-1">';
        echo '<span class="badge bg-secondary">' . humanFileSize(filesize($filePath)) . '</span> ';
        echo '<span class="badge bg-light text-dark">' . date('Y-m-d H:i', filemtime($filePath)) . '</span> ';
        echo '<a href="' . htmlspecialchars($filePath) . '" download class="btn btn-sm btn-success">تحميل</a> ';
        echo '<a href="?folder=' . urlencode($selectedFolder) . '&delete=' . urlencode($file) . '" onclick="return confirm(\'هل أنت متأكد من حذف الملف؟\');" class="btn btn-sm btn-danger">حذف</a> ';
        echo '</div></div>';
      }
      if (!$hasFiles) {
        echo '<div class="alert alert-info">لا توجد ملفات في هذا المجلد.</div>';
      }
      ?>
    </div>
  <?php else: ?>
    <div class="alert alert-secondary">اختر مجلدًا لعرض الملفات والصور بداخله.</div>
  <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox-plus-jquery.min.js"></script>
</body>
</html>
