<?php include 'sidebar.php'; ?>
<div style="margin-right: 270px; padding: 30px;">
  <h2>🖼️ رفع ملفات</h2>
  <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
    <input type="file" name="myfiles[]" multiple required class="form-control mb-2">
    <button type="submit" name="upload" class="btn btn-primary">رفع</button>
  </form>

  <?php
  if (isset($_POST['upload'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir);
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'pdf', 'txt', 'doc', 'docx'];
    $maxSize = 10 * 1024 * 1024; // 10MB
    $success = [];
    $errors = [];
    foreach ($_FILES['myfiles']['name'] as $i => $name) {
      if (!$_FILES['myfiles']['name'][$i]) continue;
      $fileName = basename($name);
      $fileTmp = $_FILES['myfiles']['tmp_name'][$i];
      $fileSize = $_FILES['myfiles']['size'][$i];
      $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
      if (!in_array($fileExt, $allowedTypes)) {
        $errors[] = "❌ الملف $fileName نوعه غير مسموح.";
        continue;
      }
      if ($fileSize > $maxSize) {
        $errors[] = "❌ الملف $fileName حجمه أكبر من المسموح (10MB).";
        continue;
      }
      $targetFile = $targetDir . $fileName;
      if (move_uploaded_file($fileTmp, $targetFile)) {
        $success[] = "✔️ تم رفع الملف $fileName بنجاح.";
      } else {
        $errors[] = "❌ فشل رفع الملف $fileName.";
      }
    }
    if ($success) {
      echo '<div class="alert alert-success">' . implode('<br>', $success) . '</div>';
    }
    if ($errors) {
      echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }
  }
  ?>
</div>
