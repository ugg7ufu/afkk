<?php include 'sidebar.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>رفع مجلد كامل</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body { background: #f3f4f6; font-family: 'Tajawal', sans-serif; }
    .card-upload { max-width: 500px; margin: 40px auto; border-radius: 18px; box-shadow: 0 5px 30px rgba(0,0,0,0.08); }
    .folder-list { max-height: 120px; overflow-y: auto; }
  </style>
  <script>
    function updateFileCount(input) {
      document.getElementById('fileCount').innerText = input.files.length ? (input.files.length + ' ملف/ملفات محددة') : '';
    }
    function showNewFolderInput(sel) {
      document.getElementById('new_folder_input').style.display = sel.value=='new' ? 'block' : 'none';
    }
  </script>
</head>
<body>
<div style="margin-right: 270px;">
  <div class="card card-upload p-4">
    <h2 class="mb-3 text-center">📁 رفع مجلد كامل</h2>
    <ol class="mb-4 text-muted small">
      <li>اختر الملفات أو المجلدات من جهازك.</li>
      <li>اختر مجلد الحفظ أو أنشئ مجلدًا جديدًا.</li>
      <li>اضغط على زر <b>رفع المجلد</b>.</li>
    </ol>
    <?php
    $baseDir = 'uploads/';
    $folders = array_filter(glob($baseDir . '*'), 'is_dir');
    $msg = '';
    if (isset($_POST['new_folder']) && $_POST['target_folder'] === 'new') {
      $folderName = preg_replace('/[^a-zA-Z0-9-_]/u', '_', $_POST['new_folder']);
      $newFolderPath = $baseDir . $folderName;
      if (is_dir($newFolderPath)) {
        $msg = "<div class='alert alert-warning'>⚠️ المجلد موجود بالفعل!</div>";
      }
    }
    ?>
    <form action="" method="POST" enctype="multipart/form-data" class="mb-3">
      <div class="mb-3">
        <input type="file" name="files[]" webkitdirectory directory multiple class="form-control" required onchange="updateFileCount(this)">
        <div class="form-text text-primary" id="fileCount"></div>
      </div>
      <div class="mb-3">
        <label class="mb-1">اختر مجلد الحفظ:</label>
        <select name="target_folder" id="target_folder" class="form-select" onchange="showNewFolderInput(this)" required>
          <option value="">اختر مجلد...</option>
          <?php foreach ($folders as $folder): ?>
            <option value="<?= htmlspecialchars(basename($folder)) ?>" <?= (isset($_POST['target_folder']) && $_POST['target_folder']===basename($folder))?'selected':'' ?>><?= htmlspecialchars(basename($folder)) ?></option>
          <?php endforeach; ?>
          <option value="new" <?= (isset($_POST['target_folder']) && $_POST['target_folder']==='new')?'selected':'' ?>>مجلد جديد...</option>
        </select>
        <input type="text" name="new_folder" id="new_folder_input" class="form-control mt-2" style="display:<?= (isset($_POST['target_folder']) && $_POST['target_folder']==='new')?'block':'none' ?>;" placeholder="اسم المجلد الجديد">
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success flex-fill">رفع المجلد</button>
        <button type="reset" class="btn btn-secondary flex-fill">إعادة تعيين</button>
      </div>
    </form>
    <?= $msg ?>
    <hr>
    <?php
    if (!empty($_FILES['files'])) {
        // تحديد المجلد المستهدف
        $chosenFolder = '';
        if (!empty($_POST['target_folder'])) {
            if ($_POST['target_folder'] === 'new' && !empty($_POST['new_folder'])) {
                $folderName = preg_replace('/[^a-zA-Z0-9-_]/u', '_', $_POST['new_folder']);
                $chosenFolder = $baseDir . $folderName . '/';
                if (!is_dir($chosenFolder)) mkdir($chosenFolder, 0777, true);
            } else {
                $chosenFolder = $baseDir . basename($_POST['target_folder']) . '/';
            }
        } else {
            $chosenFolder = $baseDir;
        }
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'pdf', 'txt', 'doc', 'docx'];
        $maxSize = 10 * 1024 * 1024; // 10MB
        $success = [];
        $errors = [];
        foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
            $fileName = $_FILES['files']['name'][$index];
            $fileSize = $_FILES['files']['size'][$index];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $targetPath = $chosenFolder . $fileName;
            $targetDirPath = dirname($targetPath);
            if (!is_dir($targetDirPath)) mkdir($targetDirPath, 0777, true);
            if ($fileSize > $maxSize) {
                $errors[] = "❌ الملف $fileName حجمه أكبر من المسموح (10MB).";
                continue;
            }
            if (file_exists($targetPath)) {
                $errors[] = "❌ الملف $fileName موجود بالفعل.";
                continue;
            }
            if (move_uploaded_file($tmpName, $targetPath)) {
                // حفظ بيانات الملف في قاعدة البيانات
                try {
                    $pdo = new PDO("mysql:host=localhost;dbname=my_app_data;charset=utf8mb4", "root", "");
                    $stmt = $pdo->prepare("INSERT INTO uploads (file_name, file_type, file_size, uploaded_at) VALUES (?, ?, ?, NOW())");
                    $stmt->execute([
                        ($chosenFolder !== $baseDir ? basename($chosenFolder) . '/' : '') . $fileName,
                        mime_content_type($targetPath),
                        filesize($targetPath)
                    ]);
                } catch (PDOException $e) {
                    $errors[] = "❌ خطأ قاعدة البيانات للملف $fileName: " . $e->getMessage();
                }
                $success[] = "✔️ $fileName";
            } else {
                $errors[] = "❌ $fileName";
            }
        }
        if ($success) {
            echo '<div class="alert alert-success"><b>تم رفع:</b><br>' . implode('<br>', $success) . '</div>';
        }
        if ($errors) {
            echo '<div class="alert alert-danger"><b>أخطاء:</b><br>' . implode('<br>', $errors) . '</div>';
        }
        echo '<div class="alert alert-info mt-3">عدد الملفات المرفوعة بنجاح: ' . count($success) . '<br>عدد الملفات التي فشلت: ' . count($errors) . '</div>';
    }
    ?>
  </div>
</div>
</body>
</html>

